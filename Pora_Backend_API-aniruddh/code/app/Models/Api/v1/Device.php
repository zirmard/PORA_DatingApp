<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $primaryKey = 'iDeviceId';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tiDeviceType','vDeviceToken','vDeviceUniqueId','vOSVersion','vDeviceName','vIpAddress','dLatitude','dLongitude','iCreatedAt','iUpdatedAt'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'iDeviceId','iUserId','vAccessToken'
    ];
}
