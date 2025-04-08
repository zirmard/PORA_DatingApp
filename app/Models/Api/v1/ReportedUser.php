<?php

namespace App\Models\Api\v1;

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

    public static function getReportedProfile($iUserId)
    {
        $reportedByMe = self::select(['iReportedUserId'])->where(['iUserId' => $iUserId])->get()->toArray();
        return array_column($reportedByMe, 'iReportedUserId');
    }

}
