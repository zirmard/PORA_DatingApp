<?php

namespace App\Models\Backend;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'backend';
    protected $primaryKey = 'iAdminId';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vAdminUuid','vName','vFirstName','vLastName','vEmail','txDescription','iRoleId','iLastLoginAt','iCreatedAt','iUpdatedAt'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'iAdminId','vPassword','tiStatus','tiIsDeleted','remember_token'
    ];

    protected $casts = [
        'iCreatedAt' => 'integer',
        'iUpdatedAt' => 'integer',
        'tiStatus' => 'integer',
        'tiIsDeleted' => 'integer'
    ];

    public function getAuthPassword() {
        return $this->vPassword;
    }

    public function adminRole(){
        return $this->belongsTo('App\Http\Backend\Models\Role', 'iRoleId', 'iRoleId');
    }
}
