<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterests extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'user_interests';
    protected $primaryKey = 'iUserInterestId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iInterestId',
        'iUserId',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];
}
