<?php

namespace App\Http\Controllers;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentFileController extends Controller
{
    public function upload(Request $request, Assignment $assignment)
{
    abort_unless(auth()->user()->isInstructor(), 403);

    $request->validate([
        'instructions' => ['required', 'file', 'mimes:pdf', 'max:5120'],
    ]);

    $path = $request->file('instructions')
        ->store('instructions', 'public');

    if ($assignment->instructions_path) {
        Storage::disk('public')->delete(
            $assignment->instructions_path
        );
    }

    $assignment->update([
        'instructions_path' => $path,
    ]);

    return back()->with('success', 'Instructions uploaded.');
}
}
