<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'user_images';
    protected $primaryKey = 'iImageId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'vImageUuId',
        'iUserId',
        'vImageName',
        'tiIsActive',
        'tsCreatedAt',
        'tsUpdatedAt'
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
       'tsDeletedAt',
    ];

}
