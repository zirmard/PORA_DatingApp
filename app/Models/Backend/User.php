<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'users';
    protected $primaryKey = 'iUserId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'vUserUuid',
        'vFirstName',
        'vLastName',
        'vISDCode',
        'vMobileNumber',
        'vEmailId',
        'tiGender',
        'tiIsActive',
        'tsUpdatedAt',
        'tsCreatedAt'
    ];

    protected $hidden = [
        'tsDeletedAt',
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt'
    ];
}
