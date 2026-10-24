@extends('layouts.app')

@section('title', 'Assignments')

@section('content')

<div class="card">
    <h1>Assignments</h1>

    @if (auth()->user()->isInstructor())
        <a class="btn"
           href="{{ route('assignments.create') }}">
            Create New Assignment
        </a>
    @endif
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Student</th>
                <th>Grade</th>
                <th>Instructions</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->id }}</td>
                    <td>{{ $assignment->title }}</td>

                    <td>
                        {{ $assignment->student->user->name }}
                    </td>

                    <td>
                        {{ $assignment->grade ?? 'Not graded' }}
                    </td>

                    <td>
                        {{ $assignment->instructions_path
                            ? 'PDF available'
                            : 'No PDF' }}
                    </td>

                    <td>
                        <a href="{{ route(
                            'assignments.show',
                            $assignment
                        ) }}">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        No assignments available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $assignments->links() }}
</div>

@endsection
