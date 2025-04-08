<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContactReason extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'user_contact_reasons';
    protected $primaryKey = 'iUserContactReasonId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'iContactReasonId',
        'txDetails',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];

    protected $casts = [
        'iContactReasonId' => 'integer'
    ];
}
