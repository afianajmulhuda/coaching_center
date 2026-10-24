<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the batches.
     */
    public function index(Request $request)
    {
        $query = Batch::with('class');

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Search by batch name
        if ($request->filled('search')) {
            $query->where('batch_name', 'like', "%{$request->search}%");
        }

        $batches = $query->orderBy('class_id', 'asc')->paginate(10);
        $classes = ClassModel::all();

        return view('batches.index', compact('batches', 'classes'));
    }

    /**
     * Show the form for creating a new batch.
     */
    public function create()
    {
        $classes = ClassModel::all();
        return view('batches.create', compact('classes'));
    }

    /**
     * Store a newly created batch in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'batch_name'   => 'required|string|max:50',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        Batch::create([
            'class_id'     => $request->class_id,
            'batch_name'   => $request->batch_name,
            'max_capacity' => $request->max_capacity ?? 40,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    /**
     * Show the form for editing the specified batch.
     */
    public function edit(Batch $batch)
    {
        $classes = ClassModel::all();
        return view('batches.edit', compact('batch', 'classes'));
    }

    /**
     * Update the specified batch in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'batch_name'   => 'required|string|max:50',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        $batch->update([
            'class_id'     => $request->class_id,
            'batch_name'   => $request->batch_name,
            'max_capacity' => $request->max_capacity ?? 40,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified batch from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}