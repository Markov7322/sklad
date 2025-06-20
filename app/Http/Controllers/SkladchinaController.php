<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skladchina;
use App\Models\Category;
use App\Models\User;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\SkladchinaImage;
use App\Notifications\SkladchinaJoined;
use App\Notifications\SkladchinaPaid;
use App\Notifications\SkladchinaStatusChanged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SkladchinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status');
        $skladchinas = Skladchina::with('category', 'organizer', 'images')
            ->when($status, fn($q) => $q->where('status', $status))
            ->paginate();
        if ($skladchinas->first()?->image_path) {
            request()->attributes->set('preload_image', $skladchinas->first()->image_path);
        }
        $isAdmin = request()->routeIs('admin.*');
        $view = $isAdmin ? 'admin.skladchinas.index' : 'skladchinas.index';
        $viewMode = request('view', $isAdmin ? 'table' : 'cards');
        $statuses = Skladchina::statuses();

        return view($view, compact('skladchinas', 'viewMode', 'statuses', 'status'));
    }

    public function my()
    {
        $viewMode = request('view', 'cards');

        return redirect()->route('account.participations', [
            'tab' => 'organizing',
            'view' => $viewMode,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('skladchinas.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'photos' => 'nullable|array|max:20',
            'photos.*' => 'image',
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string|in:' . implode(',', array_keys(Skladchina::statuses())),
            'attachment' => 'nullable|url',
        ]);

        if (! in_array($request->user()->role, ['admin', 'moderator', 'organizer'], true)) {
            unset($data['attachment']);
        }

        if ($request->hasFile('image')) {
            $saved = \App\Services\ImageService::saveUploadedAsWebp($request->file('image'), 'covers');
            $data['image_path'] = $saved['path'];
            $data['image_links'] = $saved['links'];
        }

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        $data['organizer_id'] = Auth::id();
        $data['status'] = $data['status'] ?? Skladchina::STATUS_DONATION;

        $skladchina = Skladchina::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $saved = \App\Services\ImageService::saveUploadedAsWebp($photo, 'skladchina_photos');
                $skladchina->images()->create([
                    'path' => $saved['path'],
                    'image_links' => $saved['links'],
                    'position' => $index,
                ]);
            }
        }


        return redirect()->route('skladchinas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Skladchina $skladchina)
    {
        $skladchina->load('category', 'organizer', 'participants', 'images');

        $gallery = collect([$skladchina->image_path])
            ->merge($skladchina->images->pluck('path'))
            ->filter();

        $seo = [
            'title' => $skladchina->title,
            'description' => \Illuminate\Support\Str::limit(strip_tags($skladchina->description), 160),
            'image' => $gallery->first(),
            'jsonLd' => json_encode([
                '@context' => 'https://schema.org/',
                '@type' => 'Product',
                'name' => $skladchina->title,
                'image' => $gallery->map(fn($img) => asset('images/800/'.str_replace('.webp', '.avif', $img)))->toArray(),
                'description' => \Illuminate\Support\Str::limit(strip_tags($skladchina->description), 160),
                'sku' => $skladchina->id,
                'brand' => ['@type' => 'Brand', 'name' => config('app.name')],
                'category' => $skladchina->category->name ?? '',
                'offers' => [
                    '@type' => 'Offer',
                    'url' => url()->current(),
                    'priceCurrency' => 'RUB',
                    'price' => (string) $skladchina->member_price,
                    'availability' => 'https://schema.org/InStock',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        $repeatDiscount = (float) Setting::value('repeat_discount_percent', 40);

        if ($gallery->first()) {
            request()->attributes->set('preload_image', $gallery->first());
        }

        return view('skladchinas.show', compact('skladchina', 'repeatDiscount', 'gallery', 'seo'));
    }

    /**
     * Join to skladchina.
     */
    public function join(Skladchina $skladchina)
    {
        $skladchina->participants()->syncWithoutDetaching([
            Auth::id() => ['paid' => false],
        ]);

        if ($skladchina->organizer) {
            $skladchina->organizer->notify(new SkladchinaJoined($skladchina, Auth::user()));
        }

        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Pay participation from user balance.
     */
    public function pay(Request $request, Skladchina $skladchina)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $pivot = $skladchina->participants()->where('user_id', $user->id)->first()?->pivot;
        if (! $pivot || $pivot->paid) {
            return redirect()->route('skladchinas.show', $skladchina);
        }

        if ($user->balance < $skladchina->member_price) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'insufficient_balance'], 400);
            }
            return redirect()->route('skladchinas.show', $skladchina);
        }

        $user->balance -= $skladchina->member_price;
        $user->save();
        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$skladchina->member_price,
            'description' => 'Оплата складчины ' . $skladchina->name,
        ]);

        $percent = (float) Setting::value('organizer_share_percent', 70);
        $organizerPart = $skladchina->member_price * $percent / 100;
        if ($skladchina->organizer) {
            $skladchina->organizer->balance += $organizerPart;
            $skladchina->organizer->save();
            Transaction::create([
                'user_id' => $skladchina->organizer->id,
                'amount' => $organizerPart,
                'description' => 'Доход от складчины ' . $skladchina->name,
            ]);
            $skladchina->organizer->notify(new SkladchinaPaid($skladchina, $user));
        }

        $days = (int) Setting::value('default_access_days', 30);
        $skladchina->participants()->updateExistingPivot($user->id, [
            'paid' => true,
            'access_until' => $days > 0 ? now()->addDays($days) : null,
        ]);

        $skladchina->status = Skladchina::STATUS_AVAILABLE;
        $skladchina->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Renew access with discount.
     */
    public function renew(Skladchina $skladchina)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $pivot = $skladchina->participants()->where('user_id', $user->id)->first()?->pivot;
        if (! $pivot || ! $pivot->paid || ! $pivot->access_until || now()->lte($pivot->access_until)) {
            return redirect()->route('skladchinas.show', $skladchina);
        }

        $discount = (float) Setting::value('repeat_discount_percent', 40);
        $price = $skladchina->member_price * $discount / 100;
        if ($user->balance < $price) {
            return redirect()->route('skladchinas.show', $skladchina);
        }

        $user->balance -= $price;
        $user->save();
        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$price,
            'description' => 'Повторное участие ' . $skladchina->name,
        ]);

        $percent = (float) Setting::value('organizer_share_percent', 70);
        $organizerPart = $price * $percent / 100;
        if ($skladchina->organizer) {
            $skladchina->organizer->balance += $organizerPart;
            $skladchina->organizer->save();
            Transaction::create([
                'user_id' => $skladchina->organizer->id,
                'amount' => $organizerPart,
                'description' => 'Доход от повторного участия ' . $skladchina->name,
            ]);
        }

        $days = (int) Setting::value('default_access_days', 30);
        $skladchina->participants()->updateExistingPivot($user->id, [
            'access_until' => $days > 0 ? now()->addDays($days) : null,
        ]);

        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skladchina $skladchina)
    {
        if (Auth::user()->role === 'organizer' && $skladchina->organizer_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('skladchinas.edit', compact('skladchina', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skladchina $skladchina)
    {
        if (Auth::user()->role === 'organizer' && $skladchina->organizer_id !== Auth::id()) {
            abort(403);
        }
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'photos' => 'nullable|array|max:20',
            'photos.*' => 'image',
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string|in:' . implode(',', array_keys(Skladchina::statuses())),
            'attachment' => 'nullable|url',
        ]);
        if ($request->hasFile('image')) {
            $saved = \App\Services\ImageService::saveUploadedAsWebp($request->file('image'), 'covers');
            $data['image_path'] = $saved['path'];
            $data['image_links'] = $saved['links'];
        }

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);

        if (! in_array($request->user()->role, ['admin', 'moderator', 'organizer'], true)) {
            unset($data['attachment']);
        }

        $oldStatus = $skladchina->status;
        $data['status'] = $data['status'] ?? Skladchina::STATUS_DONATION;
        $skladchina->update($data);

        if ($request->hasFile('photos')) {
            $start = $skladchina->images()->max('position') + 1;
            foreach ($request->file('photos') as $index => $photo) {
                $saved = \App\Services\ImageService::saveUploadedAsWebp($photo, 'skladchina_photos');
                $skladchina->images()->create([
                    'path' => $saved['path'],
                    'image_links' => $saved['links'],
                    'position' => $start + $index,
                ]);
            }
        }

        if ($oldStatus !== $skladchina->status) {
            $participants = $skladchina->participants()
                ->wherePivot('paid', true)
                ->where('notify_status_changes', true)
                ->get();
            foreach ($participants as $participant) {
                $participant->notify(new SkladchinaStatusChanged($skladchina));
            }
        }
        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skladchina $skladchina)
    {
        if (Auth::user()->role === 'organizer' && $skladchina->organizer_id !== Auth::id()) {
            abort(403);
        }
        $skladchina->delete();
        return redirect()->route('skladchinas.index');
    }

    /**
     * Display participants for admin.
     */
    public function participants(Skladchina $skladchina)
    {
        $skladchina->load('participants');

        return view('admin.skladchinas.participants', compact('skladchina'));
    }

    /**
     * Toggle participant payment status.
     */
    public function togglePaid(Skladchina $skladchina, User $user)
    {
        $current = (bool) ($skladchina->participants()->where('user_id', $user->id)->first()?->pivot->paid);
        $skladchina->participants()->updateExistingPivot($user->id, ['paid' => ! $current]);

        return back();
    }

    public function updateAccess(Request $request, Skladchina $skladchina, User $user)
    {
        $data = $request->validate([
            'access_until' => 'nullable|date',
        ]);
        $skladchina->participants()->updateExistingPivot($user->id, [
            'access_until' => $data['access_until'],
        ]);

        return back();
    }
}
