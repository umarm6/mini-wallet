<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\\Models\\Transaction
 *
 * @property int $id
 * @property string $uuid
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $amount
 * @property string $commission_fee
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */


class Transaction extends Model
{

    /**
     * @var list<string>
     */

    protected $fillable =[
        'uuid',
        'sender_id',
        'receiver_id',
        'amount',
        'commission_fee',
        'status',
        'reference'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
