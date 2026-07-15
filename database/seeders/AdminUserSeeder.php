<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@bidcommand.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $salesManager = User::updateOrCreate(
            ['email' => 'sales.manager@bidcommand.test'],
            [
                'name' => 'Sales Manager Demo',
                'password' => Hash::make('password'),
                'role' => 'sales_manager',
                'status' => 'active',
                'created_by' => $admin->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'project.manager@bidcommand.test'],
            [
                'name' => 'Project Manager Demo',
                'password' => Hash::make('password'),
                'role' => 'project_manager',
                'status' => 'active',
                'created_by' => $salesManager->id,
            ]
        );
    }
}
