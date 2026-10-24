<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Section;

class SectionPerformanceController extends Controller
{
    private function measure(callable $callback)
    {
        $queries = [];

        DB::listen(function ($query) use (&$queries) {
            $queries[] = [
                'sql' => $query->sql,
                'time_ms' => $query->time,
            ];
        });

        $data = $callback();

        return response()->json([
            'total_queries' => count($queries),
            'total_query_time_ms' => round(
                array_sum(array_column($queries, 'time_ms')),
                2
            ),
            'data' => $data,
            'queries' => $queries,
        ]);
    }

    // Endpoint A: N+1 queries
    public function unoptimized($id)
    {
        return $this->measure(function () use ($id) {
            $section = Section::findOrFail($id);
            $students = $section->students;

            $results = [];

            foreach ($students as $student) {
                $exams = $student->assignments()
                    ->where('title', 'Exam')
                    ->get();

                $results[] = [
                    'student_id' => $student->id,
                    'exams' => $exams,
                ];
            }

            return $results;
        });
    }

    // Endpoint B: Constrained eager loading
    public function optimized($id)
    {
        return $this->measure(function () use ($id) {
            $section = Section::with([
                'students.assignments' => function ($query) {
                    $query->where('title', 'Exam');
                }
            ])->findOrFail($id);

            return $section->students->map(function ($student) {
                return [
                    'student_id' => $student->id,
                    'exams' => $student->assignments,
                ];
            });
        });
    }
}
