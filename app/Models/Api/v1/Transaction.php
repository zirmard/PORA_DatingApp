<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'transaction';
    protected $primaryKey = 'iTransactionId';
    public $timestamps = false;

    protected $fillable = [
        'iTransactionId',
        'iUserId',
        'iSubscriptionPlanId',
        'iStartAt',
        'iExpiredAt',
        'txReceiptData',
        'tiIsActive',
        'tsCreatedAt',
        'tsUpdatedAt'
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt',
    ];

    protected $hidden = [
       'tsDeletedAt',
    ];
}
