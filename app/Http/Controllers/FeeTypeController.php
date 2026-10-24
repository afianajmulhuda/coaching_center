<?php

namespace App\Http\Controllers;

use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    /**
     * Display a listing of fee types.
     */
    public function index(Request $request)
    {
        $query = FeeType::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $feeTypes = $query->orderBy('id', 'asc')->paginate(10);

        return view('fee_types.index', compact('feeTypes'));
    }

    /**
     * Show the form for creating a new fee type.
     */
    public function create()
    {
        return view('fee_types.create');
    }

    /**
     * Store a newly created fee type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:100|unique:fee_types,title',
            'default_amount' => 'required|numeric|min:0',
        ]);

        FeeType::create([
            'title'          => $request->title,
            'default_amount' => $request->default_amount,
        ]);

        return redirect()->route('fee-types.index')->with('success', 'Fee type created successfully.');
    }

    /**
     * Show the form for editing the specified fee type.
     */
    public function edit(FeeType $feeType)
    {
        return view('fee_types.edit', compact('feeType'));
    }

    /**
     * Update the specified fee type in storage.
     */
    public function update(Request $request, FeeType $feeType)
    {
        $request->validate([
            'title'          => 'required|string|max:100|unique:fee_types,title,' . $feeType->id,
            'default_amount' => 'required|numeric|min:0',
        ]);

        $feeType->update([
            'title'          => $request->title,
            'default_amount' => $request->default_amount,
        ]);

        return redirect()->route('fee-types.index')->with('success', 'Fee type updated successfully.');
    }

    /**
     * Remove the specified fee type from storage.
     */
    public function destroy(FeeType $feeType)
    {
        $feeType->delete();

        return redirect()->route('fee-types.index')->with('success', 'Fee type deleted successfully.');
    }
}