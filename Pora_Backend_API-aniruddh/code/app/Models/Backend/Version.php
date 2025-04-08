<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;
    protected $primaryKey = 'iVersionId';

    const CREATED_AT = 'tsCreatedAt';
    const UPDATED_AT = 'tsUpdatedAt';

    public $fillable = ['fVersion'];
    protected $hidden = [
        'tsCreatedAt', 'tsUpdatedAt',
    ];
}
