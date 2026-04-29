<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = \App\Models\Department::first();
        if (!$department) {
            $department = \App\Models\Department::create([
                'name' => 'General Studies',
                'code' => 'GEN',
            ]);
        }

        $this->createUser('admin', 'System Admin', 'admin@example.com', $department);
        
        $supervisorUser = $this->createUser('supervisor', 'Dr. Supervisor', 'supervisor@example.com', $department);
        \App\Models\Supervisor::firstOrCreate(
            ['user_id' => $supervisorUser->id],
            ['specialization' => 'AI & Machine Learning']
        );

        $studentUser = $this->createUser('student', 'John Student', 'student@example.com', $department);
        \App\Models\Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            ['student_id_number' => 'STD2026001', 'program' => 'BSCS']
        );

        // New Roles
        $cosupervisorUser = $this->createUser('cosupervisor', 'Dr. Co-Supervisor', 'cosupervisor@example.com', $department);
        \App\Models\Supervisor::firstOrCreate(
            ['user_id' => $cosupervisorUser->id],
            ['specialization' => 'Data Science', 'max_load' => 3]
        );

        $this->createUser('coordinator', 'Dept. Coordinator', 'coordinator@example.com', $department);
        $this->createUser('examiner', 'Dr. Examiner', 'examiner@example.com', $department);
        $this->createUser('librarian', 'Ms. Librarian', 'librarian@example.com', $department);
    }

    private function createUser($roleName, $name, $email, $department)
    {
        $role = \App\Models\Role::where('name', $roleName)->first();
        if (!$role) {
            // Fallback just in case, though RoleSeeder should handle it
            $role = \App\Models\Role::create(['name' => $roleName, 'description' => ucfirst($roleName)]);
        }

        return \App\Models\User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt('password'),
                'role_id' => $role->id,
                'department_id' => $department ? $department->id : null,
            ]
        );
    }
}
