<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LoveLanguages extends Model
{
    use HasFactory,
        SoftDeletes;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'love_languages';
    protected $primaryKey = 'iLoveLanguageId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'vLoveLanguageUuid',
        'vLoveLanguage',
        'vLoveLanguageLogo',
        'tiIsActive',
        'tsUpdatedAt',
        'tsCreatedAt'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'tsDeletedAt',
    ];

    protected $dates = [
        'tsCreatedAt',
        'tsUpdatedAt'
    ];

}
