<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\AssignmentResource;
use App\Models\Student;
use App\Models\Assignment;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function students(Request $request)
    {
        abort_unless($request->user()->isInstructor(), 403);

        $students = Student::with(['user', 'sections'])
            ->paginate(40);

        return StudentResource::collection($students);
    }

    public function assignments(Request $request)
    {
        $query = Assignment::query();

        if ($request->user()->isStudent()) {
            $query->where(
                'student_id',
                $request->user()->student?->id ?? 0
            );
        }

        return AssignmentResource::collection(
            $query->latest()->paginate(40)
        );
    }

    public function grades(Request $request, int $id)
    {
        $student = Student::with(['user', 'sections'])
            ->find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
                'status' => 404,
            ], 404);
        }

        abort_unless(
            $request->user()->isInstructor()
            || $request->user()->student?->id === $student->id,
            403
        );

        return response()->json([
            'student' => new StudentResource($student),
            'assignments' => AssignmentResource::collection(
                $student->assignments()->get()
            )->resolve($request),
        ], 200);
    }
}
