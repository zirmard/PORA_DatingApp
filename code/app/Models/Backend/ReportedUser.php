<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedUser extends Model
{
    use HasFactory;

    const CREATED_AT = 'tsCreatedAt';

    protected $table = 'reported_users';
    protected $primaryKey = 'iUserReportId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'iUserId',
        'vUserReportUuid',
        'iReportReasonId',
        'iReportedUserId',
        'txDetails',
        'tsCreatedAt'
    ];

    protected $dates = [
        'tsCreatedAt'
    ];

    protected $casts = [
        'iReportReasonId' => 'integer',
        'iReportedUserId' => 'integer',
    ];
}
