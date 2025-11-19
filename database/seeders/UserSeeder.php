<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Demo User 1 - John Doe
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'balance' => 5000.00,
        ]);

        // Demo User 2 - Jane Smith
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'balance' => 3500.00,
        ]);

        // Demo User 3 - Bob Johnson
        User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'balance' => 7200.00,
        ]);

        // Demo User 4 - Alice Brown
        User::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
            'balance' => 2100.00,
        ]);

        // Demo User 5 - Charlie Wilson
        User::create([
            'name' => 'Charlie Wilson',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password123'),
            'balance' => 4800.00,
        ]);

        // Demo User 6 - Diana Martinez
        User::create([
            'name' => 'Diana Martinez',
            'email' => 'diana@example.com',
            'password' => Hash::make('password123'),
            'balance' => 1500.00,
        ]);

        // Demo User 7 - Eve Taylor
        User::create([
            'name' => 'Eve Taylor',
            'email' => 'eve@example.com',
            'password' => Hash::make('password123'),
            'balance' => 6300.00,
        ]);

        // Demo User 8 - Frank Anderson
        User::create([
            'name' => 'Frank Anderson',
            'email' => 'frank@example.com',
            'password' => Hash::make('password123'),
            'balance' => 3900.00,
        ]);
    }
}
