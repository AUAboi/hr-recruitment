<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Applicant',
            'username' => 'AdminApplicant',
            'email' => 'applicant@admin.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('applicant');
    }
}
