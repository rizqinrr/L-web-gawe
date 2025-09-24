<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'amount',
        'type', // credit / debit
        'is_paid',
        'bank_name',
        'bank_account_name',
        'bank_account_number' 
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
        // pengecekan transaksi ini milik user apa
    }
    //
}
