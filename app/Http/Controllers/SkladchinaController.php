<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skladchina;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class SkladchinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skladchinas = Skladchina::with('category', 'organizer')->paginate();
        return view('skladchinas.index', compact('skladchinas'));
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
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data['organizer_id'] = Auth::id();

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
        $skladchina->participants()->syncWithoutDetaching([Auth::id()]);

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
            'full_price' => 'required|numeric',
            'member_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);
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
}
