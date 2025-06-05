<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function balance(): View
    {
        return view('account.balance');
    }

    public function transactions(): View
    {
        $transactions = Auth::user()->transactions()->latest()->get();
        return view('account.transactions', compact('transactions'));
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
