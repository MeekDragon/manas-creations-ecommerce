<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Super Admin: Om Yadav
        $superAdmin = User::where('email', 'manascreationsofficial@gmail.com')
            ->orWhere('mobile', '7058466889')
            ->first() ?? new User();
        
        $superAdmin->forceFill([
            'name' => 'Om Yadav',
            'email' => 'manascreationsofficial@gmail.com',
            'mobile' => '7058466889',
            'password' => Hash::make('OmYadavSuper2026'),
            'is_admin' => true,
            'is_superadmin' => true,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ])->save();

        // 2. Standard Admin 1
        $admin1 = User::where('email', 'admin1@manascreations.in')->first() ?? new User();
        $admin1->forceFill([
            'name' => 'Atish Admin',
            'email' => 'admin1@manascreations.in',
            'mobile' => '7058466881',
            'password' => Hash::make('atishAdmin2026'),
            'is_admin' => true,
            'is_superadmin' => false,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ])->save();

        // 3. Standard Admin 2
        $admin2 = User::where('email', 'admin2@manascreations.in')->first() ?? new User();
        $admin2->forceFill([
            'name' => 'Dheeraj Admin',
            'email' => 'admin2@manascreations.in',
            'mobile' => '7058466882',
            'password' => Hash::make('dheerajAdmin2026'),
            'is_admin' => true,
            'is_superadmin' => false,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ])->save();

        // 4. Standard Admin 3
        $admin3 = User::where('email', 'admin3@manascreations.in')->first() ?? new User();
        $admin3->forceFill([
            'name' => 'Manas Admin',
            'email' => 'admin3@manascreations.in',
            'mobile' => '7058466883',
            'password' => Hash::make('manasAdmin2026'),
            'is_admin' => true,
            'is_superadmin' => false,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ])->save();
    }
}

