<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skladchina;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SkladchinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skladchinas = Skladchina::with('category', 'organizer')->paginate();
        $view = request()->routeIs('admin.*') ? 'admin.skladchinas.index' : 'skladchinas.index';
        return view($view, compact('skladchinas'));
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
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string|in:' . implode(',', array_keys(Skladchina::statuses())),
            'attachment' => 'nullable|url',
        ]);

        if (! in_array($request->user()->role, ['admin', 'moderator'], true)) {
            unset($data['attachment']);
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('covers', 'public');
        }

        $data['organizer_id'] = Auth::id();
        $data['status'] = $data['status'] ?? Skladchina::STATUS_DONATION;

        Skladchina::create($data);

        return redirect()->route('skladchinas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $skladchina = Skladchina::with('category', 'organizer', 'participants')->findOrFail($id);
        return view('skladchinas.show', compact('skladchina'));
    }

    /**
     * Join to skladchina.
     */
    public function join(string $id)
    {
        $skladchina = Skladchina::findOrFail($id);
        $skladchina->participants()->syncWithoutDetaching([
            Auth::id() => ['paid' => false],
        ]);

        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $skladchina = Skladchina::findOrFail($id);
        $categories = Category::all();
        return view('skladchinas.edit', compact('skladchina', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $skladchina = Skladchina::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string|in:' . implode(',', array_keys(Skladchina::statuses())),
            'attachment' => 'nullable|url',
        ]);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('covers', 'public');
        }

        if (! in_array($request->user()->role, ['admin', 'moderator'], true)) {
            unset($data['attachment']);
        }

        $data['status'] = $data['status'] ?? Skladchina::STATUS_DONATION;
        $skladchina->update($data);
        return redirect()->route('skladchinas.show', $skladchina);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skladchina = Skladchina::findOrFail($id);
        $skladchina->delete();
        return redirect()->route('skladchinas.index');
    }

    /**
     * Display participants for admin.
     */
    public function participants(string $id)
    {
        $skladchina = Skladchina::with('participants')->findOrFail($id);

        return view('admin.skladchinas.participants', compact('skladchina'));
    }

    /**
     * Toggle participant payment status.
     */
    public function togglePaid(string $skladchinaId, User $user)
    {
        $skladchina = Skladchina::findOrFail($skladchinaId);
        $current = (bool) $skladchina->participants()->where('user_id', $user->id)->first()->pivot->paid;
        $skladchina->participants()->updateExistingPivot($user->id, ['paid' => ! $current]);

        return back();
    }
}
