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

        $participating = $user->skladchinas()->with('category')->get();

        $organizing = collect();
        if (in_array($user->role, ['admin', 'moderator', 'organizer'], true)) {
            $organizing = $user->organizedSkladchinas()->with('category', 'images')->get();
        }

        return view('account.participations', [
            'tab' => $tab,
            'viewMode' => $viewMode,
            'participating' => $participating,
            'organizing' => $organizing,
        ]);
    }
}
