<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoveLanguages extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'user_love_languages';
    protected $primaryKey = 'iUserLoveLanguageId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iLoveLanguageId',
        'iUserId',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];
}
