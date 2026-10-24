<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of classes.
     */
    public function index(Request $request)
    {
        $query = ClassModel::query();

        if ($request->filled('search')) {
            $query->where('class_name', 'like', "%{$request->search}%");
        }

        $classes = $query->orderBy('id', 'asc')->paginate(10);

        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:50|unique:classes,class_name',
        ]);

        ClassModel::create([
            'class_name' => $request->class_name,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(ClassModel $class)
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'class_name' => 'required|string|max:50|unique:classes,class_name,' . $class->id,
        ]);

        $class->update([
            'class_name' => $request->class_name,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}