<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Demo Instructor',
            'email' => 'instructor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
        ]);

        $titles = [
            'Exam',
            'Quiz 1',
            'Quiz 2',
            'Activity 1',
            'Activity 2',
            'Activity 3',
            'Project',
            'Research',
            'Laboratory',
            'Final Output',
        ];

        for ($s = 1; $s <= 10; $s++) {

            $section = Section::create([
                'name' => "Section $s",
            ]);

            for ($i = 1; $i <= 40; $i++) {

                $number = ($s - 1) * 40 + $i;

                $user = User::create([
                    'name' => "Student $number",
                    'email' => "student$number@example.com",
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                ]);

                $section->students()->attach($student->id);

                foreach ($titles as $title) {
                    Assignment::create([
                        'student_id' => $student->id,
                        'title' => $title,
                        'grade' => random_int(60, 100),
                    ]);
                }
            }
        }
    }
}
