<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarkController extends Controller
{
    /**
     * Affiche la vue du tableau de bord avec les marques et les produits.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $marks = Mark::with('products')->get();
        $products = Product::latest()->get();

        return Inertia::render('Dashboard', compact('marks', 'products'));
    }

    /**
     * Enregistre une nouvelle marque avec ses produits associés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'products' => 'required|array',
        ]);

        $mark = Mark::create([
            'name' => $request->name,
        ]);

        $mark->products()->sync($request->products);

        return redirect()->route('dashboard');
    }

    /**
     * Met à jour une marque existante avec ses produits associés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Mark $mark)
    {
        $request->validate([
            'name' => 'required|string',
            'products' => 'required|array',
        ]);

        $mark->update([
            'name' => $request->name,
        ]);

        $mark->products()->sync($request->products);

        return redirect()->route('dashboard');
    }

    /**
     * Supprime une marque et détache ses produits associés.
     *
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mark $mark)
    {
        $mark->products()->detach();
        $mark->delete();

        return redirect()->route('dashboard');
    }
}
