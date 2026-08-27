<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = 'Admin Starbluu';
        $admin->email = 'admin@starbluu.com';
        $admin->password = bcrypt('admin123');
        $admin->role = 'admin';
        $admin->save();
    }
}
