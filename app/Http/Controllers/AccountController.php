<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function balance(): View
    {
        $transactions = Auth::user()->transactions()->latest()->paginate(10);
        return view('account.balance', compact('transactions'));
    }

    public function transactions(): View
    {
        return $this->balance();
    }

    public function participations(): View
    {
        $user = Auth::user();
        $tab = request('tab', 'participating');
        $viewMode = request('view', 'cards');
        $status = request('status');

        $participating = $user->skladchinas()
            ->with('category')
            ->when($status, fn($q) => $q->where('skladchinas.status', $status))
            ->get();

        $organizing = collect();
        if (in_array($user->role, ['admin', 'moderator', 'organizer'], true)) {
            $organizing = $user->organizedSkladchinas()
                ->with('category', 'images')
                ->when($status, fn($q) => $q->where('status', $status))
                ->get();
        }

        $firstImage = $participating->first()?->image_path ?? $organizing->first()?->image_path;
        if ($firstImage) {
            request()->attributes->set('preload_image', $firstImage);
        }

        $statuses = \App\Models\Skladchina::statuses();

        return view('account.participations', [
            'tab' => $tab,
            'viewMode' => $viewMode,
            'participating' => $participating,
            'organizing' => $organizing,
            'statuses' => $statuses,
            'status' => $status,
        ]);
    }

    public function notifications(): View
    {
        return view('account.notifications');
    }

    public function updateNotifications(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'notify_status_changes' => 'sometimes|boolean',
            'notify_site' => 'sometimes|boolean',
            'notify_balance_changes' => 'sometimes|boolean',
        ]);

        $request->user()->update([
            'notify_status_changes' => $data['notify_status_changes'] ?? false,
            'notify_site' => $data['notify_site'] ?? false,
            'notify_balance_changes' => $data['notify_balance_changes'] ?? false,
        ]);

        return back();
    }
}
