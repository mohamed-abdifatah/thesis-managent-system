<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'student', 'description' => 'University Student'],
            ['name' => 'supervisor', 'description' => 'Faculty Supervisor'],
            ['name' => 'cosupervisor', 'description' => 'Co-Supervisor'],
            ['name' => 'coordinator', 'description' => 'Department Coordinator'],
            ['name' => 'examiner', 'description' => 'Thesis Examiner'],
            ['name' => 'librarian', 'description' => 'Library Officer'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
