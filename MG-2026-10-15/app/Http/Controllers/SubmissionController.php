<?php
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    // Show all submissions for instructors,
    // or only the logged-in student's submissions.
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Submission::with([
            'assignment',
            'student.user',
        ]);

        if ($user->isStudent()) {
            $query->where(
                'student_id',
                $user->student?->id ?? 0
            );
        }

        $submissions = $query
            ->latest()
            ->paginate(20);

        return view('submissions.index', compact('submissions'));
    }

    // Upload student work to private storage.
    public function store(Request $request, Assignment $assignment)
    {
        $user = $request->user();

        abort_unless(
            $user->isStudent()
            && $user->student?->id === $assignment->student_id,
            403
        );

        $request->validate([
            'submission' => [
                'required',
                'file',
                'mimes:pdf,zip,docx',
                'max:10240',
            ],
        ]);

        $file = $request->file('submission');

        // Laravel's local disk stores files under
        // storage/app/private by default.
        $path = $file->store('submissions', 'local');

        $existing = Submission::where([
            'assignment_id' => $assignment->id,
            'student_id' => $user->student->id,
        ])->first();

        try {
            $submission = Submission::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $user->student->id,
                ],
                [
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                ]
            );
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        if ($existing && $existing->file_path !== $path) {
            Storage::disk('local')->delete($existing->file_path);
        }

        return redirect()
            ->route('assignments.show', $assignment)
            ->with('success', 'Submission uploaded successfully.');
    }

    // Download only if the user is authorized.
    public function download(Request $request, Submission $submission)
    {
        $user = $request->user();

        $allowed = $user->isInstructor()
            || (
                $user->isStudent()
                && $user->student?->id === $submission->student_id
            );

        abort_unless($allowed, 403);

        if (! Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        return Storage::disk('local')->download(
            $submission->file_path,
            $submission->original_filename
        );
    }
}
