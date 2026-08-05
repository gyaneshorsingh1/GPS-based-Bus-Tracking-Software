<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Assign seeded students to their school's buses.
     */
    public function run(): void
    {
        $schools = School::orderBy('id')->get();

        if ($schools->isEmpty()) {
            $this->command->error('No school found. Run SchoolSeeder before StudentSeeder.');

            return;
        }

        DB::transaction(function () use ($schools) {
            foreach ($schools as $school) {
                $buses = Bus::where('school_id', $school->id)
                    ->orderBy('id')
                    ->get();

                if ($buses->isEmpty()) {
                    continue;
                }

                $students = Student::where('school_id', $school->id)
                    ->orderBy('id')
                    ->get();

                foreach ($students as $index => $student) {
                    $student->update([
                        'bus_id' => $buses->get($index % $buses->count())->id,
                    ]);
                }
            }
        });

        $this->command->info('Students assigned to buses successfully.');
    }
}
