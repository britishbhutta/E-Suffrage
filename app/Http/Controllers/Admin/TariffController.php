<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tariff;

class TariffController extends Controller
{
    public function create()
    {
        return view('admin.tariff.add-tariff');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'note'             => 'nullable|string',
            'features'         => 'required|array|min:1',
            'features.*'       => 'required|string|max:255',
            'price_cents'      => 'required|numeric|min:0',
            'currency'         => 'required|string|max:10',
            'available_votes'  => 'required|integer|min:0',
        ]);

        // dd($request->all());

        Tariff::create([
            'title'           => $request->title,
            'description'     => $request->description,
            'note'            => $request->note,
            'features'        => $request->features,
            'price_cents'     => $request->price_cents,
            'currency'        => $request->currency,
            'available_votes' => $request->available_votes,
        ]);

        return redirect()->route('admin.tariff.index')->with('success', 'Tariff added successfully!');
    }

    public function index()
    {
        $tariffs = Tariff::all();

        return view('admin.tariff.show-tariff', compact('tariffs'));
    }

    public function edit($id)
    {
        $tariff = Tariff::findOrFail($id);
        return view('admin.tariff.edit-tariff', compact('tariff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'note'             => 'nullable|string',
            'features'         => 'required|array|min:1',
            'features.*'       => 'required|string|max:255',
            'price_cents'      => 'required|numeric|min:0',
            'currency'         => 'required|string|max:10',
            'available_votes'  => 'required|integer|min:0',
        ]);

        $tariff = Tariff::findOrFail($id);

        $tariff->update([
            'title'           => $request->title,
            'description'     => $request->description,
            'note'            => $request->note,
            'features'        => $request->features, 
            'price_cents'     => $request->price_cents,
            'currency'        => $request->currency,
            'available_votes' => $request->available_votes,
        ]);

        return redirect()->route('admin.tariff.index')->with('success', 'Tariff updated successfully!');
    }

    public function destroy($id)
    {
        $tariff = Tariff::findOrFail($id);
        $tariff->delete();

        return redirect()->route('admin.tariff.index')->with('success', 'Tariff deleted successfully!');
    }

}
