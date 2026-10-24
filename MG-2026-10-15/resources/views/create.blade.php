@extends('layouts.app')

@section('title', 'Create Assignment')

@section('content')

<div class="card">
    <h1>Create Assignment</h1>

    <form method="POST"
          action="{{ route('assignments.store') }}"
          enctype="multipart/form-data">

        @csrf

        <label for="student_id">Student</label>

        <select name="student_id"
                id="student_id"
                required>
            <option value="">Select a student</option>

            @foreach ($students as $student)
                <option value="{{ $student->id }}"
                    @selected(old('student_id') == $student->id)>
                    {{ $student->user->name }}
                    (ID: {{ $student->id }})
                </option>
            @endforeach
        </select>

        <label for="title">Assignment Title</label>

        <input type="text"
               id="title"
               name="title"
               value="{{ old('title') }}"
               required>

        <label for="instructions">
            Instructions PDF (maximum 5 MB)
        </label>

        <input type="file"
               id="instructions"
               name="instructions"
               accept=".pdf">

        <button type="submit">
            Create Assignment
        </button>
    </form>
</div>

@endsection
