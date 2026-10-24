@extends('layouts.app')

@section('title', 'Submissions')

@section('content')

<div class="card">
    <h1>Student Submissions</h1>

    <p class="muted">
        @if (auth()->user()->isInstructor())
            Viewing submissions from all students.
        @else
            Viewing your own submissions.
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Assignment</th>
                <th>Filename</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($submissions as $submission)
                <tr>
                    <td>{{ $submission->id }}</td>

                    <td>
                        {{ $submission->student->user->name }}
                    </td>

                    <td>
                        {{ $submission->assignment->title }}
                    </td>

                    <td>
                        {{ $submission->original_filename }}
                    </td>

                    <td>
                        {{ $submission->created_at->format('M d, Y h:i A') }}
                    </td>

                    <td>
                        <a href="{{ route(
                            'submissions.download',
                            $submission
                        ) }}">
                            Download
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        No student submissions found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $submissions->links() }}
</div>

@endsection
