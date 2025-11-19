<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $john = User::where('email', 'john@example.com')->first();      // ID: 1
        $jane = User::where('email', 'jane@example.com')->first();      // ID: 2
        $bob = User::where('email', 'bob@example.com')->first();        // ID: 3
        $alice = User::where('email', 'alice@example.com')->first();    // ID: 4
        $charlie = User::where('email', 'charlie@example.com')->first(); // ID: 5
        $diana = User::where('email', 'diana@example.com')->first();    // ID: 6
        $eve = User::where('email', 'eve@example.com')->first();        // ID: 7
        $frank = User::where('email', 'frank@example.com')->first();    // ID: 8

        // Transaction 1: John sends $500 to Jane (2 days ago)
        $amount = 500.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $john->id,
            'receiver_id' => $jane->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(2)->subHours(5),
            'updated_at' => Carbon::now()->subDays(2)->subHours(5),
        ]);

        // Transaction 2: Jane sends $150 to Bob (2 days ago)
        $amount = 150.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $jane->id,
            'receiver_id' => $bob->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(2)->subHours(3),
            'updated_at' => Carbon::now()->subDays(2)->subHours(3),
        ]);

        // Transaction 3: Bob sends $300 to Alice (1.5 days ago)
        $amount = 300.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $bob->id,
            'receiver_id' => $alice->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(1)->subHours(12),
            'updated_at' => Carbon::now()->subDays(1)->subHours(12),
        ]);

        // Transaction 4: Charlie sends $250 to Diana (1.5 days ago)
        $amount = 250.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $charlie->id,
            'receiver_id' => $diana->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(1)->subHours(10),
            'updated_at' => Carbon::now()->subDays(1)->subHours(10),
        ]);

        // Transaction 5: Alice sends $100 to Eve (1 day ago)
        $amount = 100.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $alice->id,
            'receiver_id' => $eve->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(1)->subHours(8),
            'updated_at' => Carbon::now()->subDays(1)->subHours(8),
        ]);

        // Transaction 6: Eve sends $450 to John (1 day ago)
        $amount = 450.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $eve->id,
            'receiver_id' => $john->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subDays(1)->subHours(5),
            'updated_at' => Carbon::now()->subDays(1)->subHours(5),
        ]);

        // Transaction 7: Frank sends $200 to Charlie (18 hours ago)
        $amount = 200.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $frank->id,
            'receiver_id' => $charlie->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(18),
            'updated_at' => Carbon::now()->subHours(18),
        ]);

        // Transaction 8: John sends $75 to Diana (16 hours ago)
        $amount = 75.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $john->id,
            'receiver_id' => $diana->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(16),
            'updated_at' => Carbon::now()->subHours(16),
        ]);

        // Transaction 9: Jane sends $320 to Frank (14 hours ago)
        $amount = 320.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $jane->id,
            'receiver_id' => $frank->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(14),
            'updated_at' => Carbon::now()->subHours(14),
        ]);

        // Transaction 10: Bob sends $550 to Eve (12 hours ago)
        $amount = 550.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $bob->id,
            'receiver_id' => $eve->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(12),
            'updated_at' => Carbon::now()->subHours(12),
        ]);

        // Transaction 11: Charlie sends $175 to Alice (10 hours ago)
        $amount = 175.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $charlie->id,
            'receiver_id' => $alice->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(10),
            'updated_at' => Carbon::now()->subHours(10),
        ]);

        // Transaction 12: Diana sends $225 to Bob (8 hours ago)
        $amount = 225.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $diana->id,
            'receiver_id' => $bob->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(8),
            'updated_at' => Carbon::now()->subHours(8),
        ]);

        // Transaction 13: Eve sends $125 to Jane (6 hours ago)
        $amount = 125.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $eve->id,
            'receiver_id' => $jane->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(6),
            'updated_at' => Carbon::now()->subHours(6),
        ]);

        // Transaction 14: Frank sends $400 to John (4 hours ago)
        $amount = 400.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $frank->id,
            'receiver_id' => $john->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(4),
            'updated_at' => Carbon::now()->subHours(4),
        ]);

        // Transaction 15: Alice sends $290 to Charlie (2 hours ago)
        $amount = 290.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $alice->id,
            'receiver_id' => $charlie->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        // Transaction 16: John sends $180 to Frank (1 hour ago)
        $amount = 180.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $john->id,
            'receiver_id' => $frank->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subHours(1),
            'updated_at' => Carbon::now()->subHours(1),
        ]);

        // Transaction 17: Jane sends $95 to Diana (30 minutes ago)
        $amount = 95.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $jane->id,
            'receiver_id' => $diana->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subMinutes(30),
            'updated_at' => Carbon::now()->subMinutes(30),
        ]);

        // Transaction 18: Bob sends $420 to Frank (15 minutes ago)
        $amount = 420.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $bob->id,
            'receiver_id' => $frank->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subMinutes(15),
            'updated_at' => Carbon::now()->subMinutes(15),
        ]);

        // Transaction 19: Charlie sends $310 to Eve (10 minutes ago)
        $amount = 310.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $charlie->id,
            'receiver_id' => $eve->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subMinutes(10),
            'updated_at' => Carbon::now()->subMinutes(10),
        ]);

        // Transaction 20: Eve sends $265 to Alice (5 minutes ago - MOST RECENT)
        $amount = 265.00;
        $fee = round($amount * 0.015, 2);
        Transaction::create([
            'sender_id' => $eve->id,
            'receiver_id' => $alice->id,
            'amount' => $amount,
            'commission_fee' => $fee,
            'status' => 'completed',
            'reference' => Str::uuid()->toString(),
            'created_at' => Carbon::now()->subMinutes(5),
            'updated_at' => Carbon::now()->subMinutes(5),
        ]);
    }
}
