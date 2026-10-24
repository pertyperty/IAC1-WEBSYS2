<?php
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentFileController extends Controller
{
    // Show assignments based on the logged-in user.
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Assignment::with(['student.user']);

        if ($user->isStudent()) {
            $query->where(
                'student_id',
                $user->student?->id ?? 0
            );
        }

        $assignments = $query
            ->latest()
            ->paginate(20);

        return view('assignments.index', compact('assignments'));
    }

    // Show the assignment creation form.
    public function create(Request $request)
    {
        abort_unless($request->user()->isInstructor(), 403);

        $students = Student::with('user')
            ->orderBy('id')
            ->get();

        return view('assignments.create', compact('students'));
    }

    // Create an assignment with optional PDF instructions.
    public function store(Request $request)
    {
        abort_unless($request->user()->isInstructor(), 403);

        $data = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'title' => ['required', 'string', 'max:255'],
            'instructions' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ]);

        $assignment = Assignment::create([
            'student_id' => $data['student_id'],
            'title' => $data['title'],
            'grade' => null,
        ]);

        if ($request->hasFile('instructions')) {
            $path = $request->file('instructions')
                ->store('instructions', 'public');

            $assignment->update([
                'instructions_path' => $path,
            ]);
        }

        return redirect()
            ->route('assignments.show', $assignment)
            ->with('success', 'Assignment created successfully.');
    }

    // Show the assignment details and its PDF.
    public function show(Request $request, Assignment $assignment)
    {
        $user = $request->user();

        abort_unless(
            $user->isInstructor()
            || $user->student?->id === $assignment->student_id,
            403
        );

        $assignment->load(['student.user', 'submissions']);

        return view('assignments.show', compact('assignment'));
    }

    // Upload or replace a PDF on an existing assignment.
    public function upload(Request $request, Assignment $assignment)
    {
        abort_unless($request->user()->isInstructor(), 403);

        $request->validate([
            'instructions' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ]);

        $path = $request->file('instructions')
            ->store('instructions', 'public');

        $oldPath = $assignment->instructions_path;

        $assignment->update([
            'instructions_path' => $path,
        ]);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('assignments.show', $assignment)
            ->with('success', 'PDF instructions uploaded.');
    }
}
