<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory,
        SoftDeletes;

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';
    const DELETED_AT = 'tsDeletedAt';

    protected $table = 'faqs';
    protected $primaryKey = 'iFaqId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'vFaqUuid',
        'vQuestionCategory',
        'vQuestion',
        'txAnswer',
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
}
