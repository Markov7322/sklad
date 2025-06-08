<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $topup = Topup::create([
            'user_id' => Auth::id(),
            'amount' => $data['amount'],
            'status' => Topup::STATUS_PENDING,
        ]);

        return redirect()->route('topups.thanks', $topup);
    }

    public function thanks(Topup $topup)
    {
        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }
        return view('topups.thanks', compact('topup'));
    }
}
