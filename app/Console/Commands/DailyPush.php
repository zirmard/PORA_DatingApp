<?php

namespace App\Console\Commands;

use App\Models\Api\v1\User;
use Illuminate\Console\Command;
use Log;

class DailyPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily push notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $manydayuse = User::select('users.iUserId', 'iLastTimeAppUsed', 'vDeviceToken')
            ->leftJoin('devices', 'users.iUserId', '=', 'devices.iUserId')
            ->where('iLastTimeAppUsed', '<=', time())->get();
            if(!empty($manydayuse)){
                $device = [];
                foreach($manydayuse as $devices){
                    if(!empty($devices->vDeviceToken)){
                        $device[] = $devices->vDeviceToken;
                    }
                }
                $fields['data'] = [
                    'title' => 'Pora',
                    'body' => 'You have not used your app for many days',
                    'badge' => 'badge',
                    'sound' => 'default',
                    'icon' => asset('theme/dist/img/logo.png'),
                ];
                // dd($device);
                $push = pushCurlCall($device, $fields);
            }
    }
}
