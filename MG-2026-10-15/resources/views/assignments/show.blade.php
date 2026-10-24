@extends('layouts.app')

@section('title', 'Assignment Details')

@section('content')

<div class="card">
    <h1>{{ $assignment->title }}</h1>

    <p>
        <strong>Assignment ID:</strong>
        {{ $assignment->id }}
    </p>

    <p>
        <strong>Student:</strong>
        {{ $assignment->student->user->name }}
    </p>

    <p>
        <strong>Grade:</strong>
        {{ $assignment->grade ?? 'Not graded' }}
    </p>
</div>

<div class="card">
    <h2>Assignment Instructions</h2>

    @if ($assignment->instructions_path)

        <iframe
            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(
                $assignment->instructions_path
            ) }}"
            title="Assignment Instructions">
        </iframe>

        <p>
            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(
                $assignment->instructions_path
            ) }}" target="_blank">
                Open PDF in New Tab
            </a>
        </p>

    @else
        <p class="muted">
            No PDF instructions have been uploaded.
        </p>
    @endif
</div>

@if (auth()->user()->isInstructor())

<div class="card">
    <h2>Upload or Replace Instructions</h2>

    <form method="POST"
          action="{{ route(
              'assignments.instructions.upload',
              $assignment
          ) }}"
          enctype="multipart/form-data">

        @csrf

        <label for="instructions">
            Select a PDF file (maximum 5 MB)
        </label>

        <input type="file"
               id="instructions"
               name="instructions"
               accept=".pdf"
               required>

        <button type="submit">
            Upload PDF
        </button>
    </form>
</div>

@endif

@if (
    auth()->user()->isStudent()
    && auth()->user()->student?->id === $assignment->student_id
)

<div class="card">
    <h2>Submit Your Work</h2>

    <p>Accepted formats: PDF, ZIP, DOCX (maximum 10 MB).</p>

    <form method="POST"
          action="{{ route(
              'submissions.store',
              $assignment
          ) }}"
          enctype="multipart/form-data">

        @csrf

        <input type="file"
               name="submission"
               accept=".pdf,.zip,.docx"
               required>

        <button type="submit">
            Upload Submission
        </button>
    </form>
</div>

@endif

<div class="card">
    <h2>Submitted Work</h2>

    @forelse ($assignment->submissions as $submission)
        <p>
            {{ $submission->original_filename }}

            <a href="{{ route(
                'submissions.download',
                $submission
            ) }}">
                Download
            </a>
        </p>
    @empty
        <p class="muted">No submissions yet.</p>
    @endforelse
</div>

@endsection
