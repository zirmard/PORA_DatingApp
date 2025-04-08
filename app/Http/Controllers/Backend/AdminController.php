<?php

namespace App\Http\Controllers\Backend;

use App\Common;
use App\Models\Api\v1\User;
use App\Models\Backend\Admin;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!Auth::guard('backend')->check()) {
            $model = new Admin();
            return view('backend.admin.login',compact('model'));
        }

        return redirect()->guest(route('backend.home'));
    }

    public function dologin(Request $request)
    {
        // Validate form data
        $this->validate($request, [
            'vEmail' => 'required|email',
            'vPassword' => 'required',
            'iTimezoneOffset' => 'required|integer'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;
        $isLogin = Auth::guard('backend')->attempt(['vEmail' => $request->vEmail, 'password' => $request->vPassword, 'tiIsDeleted' => 0], $remember_me);
        if ($isLogin) {
            $admin = Auth::guard('backend')->user();
            if ($admin->tiIsActive == 1) {
                DB::update('update admins set iLastLoginAt = ? where iAdminId = ?', [time(), $admin->iAdminId]);
                return redirect()->guest(route('backend.home'));
            } else {
                Auth::logout();
                $request->session()->flush();
                $request->session()->flash('error', 'Your account is inactive');
                return redirect()->back()->withInput($request->only('vEmail'));
            }
        }
        // Authentication failed, redirect back to the login form
        $request->session()->flash('error', 'Wrong email or password.');
        return redirect()->back()->withInput($request->only('vEmail'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route('backend.login'));
    }

    public function error()
    {
        return view('backend.admin.error');
    }

    //Change password
    public function ChangePassword(Request $request, MessageBag $message_bag)
    {
        $user = Auth::user();
        if ($request->isMethod("post")) {
            // Validate form data
            $this->validate($request, [
                'vCurrentPassword' => 'required',
                'vNewPassword' => 'required|min:8|different:vCurrentPassword',
                'vConfirmPassword' => 'required_with:vNewPassword|same:vNewPassword',
            ]);

            if (!Hash::check($request->vCurrentPassword, $user->vPassword)) {
                $message_bag->add('vCurrentPassword', __("message.old_password_wrong"));
                return Redirect::back()->withInput($request->all())->withErrors($message_bag);
            }

            $user->vPassword = Hash::make($request->vNewPassword);
            $user->save();

            FormSuccessResponse($request, "message.password_changed");
            return redirect()->route('backend.change_password');
        }
        return view('backend.admin.changepassword');
    }

    //Profile
    public function Profile(Request $request, MessageBag $message_bag)
    {
        $model = Auth::user();

        if ($request->isMethod("post")) {
            $this->validate(
                $request,
                [
                    'vName' => 'required',
                    'vEmail' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                    'vImage' => 'nullable|mimes:jpeg,png,jpg|max:2048',
                ],
                [
                    'vName.required' => 'Name field is required',
                    'vEmail.required' => 'Email field is required',
                    'vEmail.email' => 'Enter Valid Email Address',
                    'vImage.mimes' => 'Image type must be of jpeg,png or jpg',
                    'vImage.max' => 'Image size can not be greater than 2MB',
                ]
            );

            $model->fill($request->all());
            if ($request->hasFile('vImage')) {
                $file_name = $request->file('vImage')->hashName();
                Storage::delete('/public/uploads/' . $model->vImage); // Unlink the old image of the user from the storage.
                $request->file('vImage')->store('public/uploads');
                $model->vImage = $file_name;
            }
            if ($model->save()) {
                FormSuccessResponse($request, "message.profile_updated");
                return redirect()->route('backend.profile');
            }
        }
        return view('backend.admin.my-profile', compact('model'));
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('backend.admin.forgotPassword');
    }

    // Forgot Password Logistics
    public function submitForgotPasswordForm(Request $request)
    {
        $request->validate(
            [
                'vEmail' => 'required|email|exists:admins',
            ],
            [
                'vEmail.required' => 'Email cannot be blank.',
                'vEmail.email' => 'Please enter valid email address.',
                'vEmail.exists' => 'There is no user with this email address.',
            ]
        );

        try {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->vEmail,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('backend.emails.forgotPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->vEmail);
                $message->subject('Reset Password for PORA App');
            });

            Session::flash('success', 'We have e-mailed your password reset link!');
            return redirect()->route('backend.login');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    // Show Password Reset Form
    public function showResetPasswordForm($token)
    {
        $tokenExists = DB::table('password_resets')->where(['token' => $token])->first();
        if ($tokenExists) {
            return view('backend.admin.forgotPasswordLink', ['token' => $token]);
        } else {
            return view('errors.404');
        }
    }

    // Reset Password Logistics
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'vPassword' => 'required|min:8|same:vConfirmPassword',
            'vConfirmPassword' => 'required'
        ]);

        try {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'token' => $request->token
                ])
                ->first();

            if (!$updatePassword) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            $admin = Admin::where('vEmail', $updatePassword->email)
                ->update(['vPassword' => Hash::make($request->vPassword)]);

            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();

            Session::flash('success', 'Password changed successfully. You can now sign in with your new password.');
            return redirect()->route('backend.login');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function manyDay()

    {
        // Log::info('aniruddh');

        $manydayuse = User::select('users.iUserId', 'iLastTimeAppUsed', 'vDeviceToken')
            ->leftJoin('devices', 'users.iUserId', '=', 'devices.iUserId')
            ->where('iLastTimeAppUsed', '<=', time())->get();

        if (!empty($manydayuse)) {
            $device = [];
            foreach ($manydayuse as $devices) {
                if (!empty($devices->vDeviceToken)) {
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
            $push = pushCurlCall($device, $fields);

        }
    }
}

