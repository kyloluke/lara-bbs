<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // laravel 5.6 新增：https://learnku.com/laravel/t/9404/laravel-56-new-function-routing-signature
        $this->middleware('signed')->only('verify');
        // throttle 中间件是框架提供的访问频率限制功能，throttle 中间件会接收两个参数，这两个参数决定了在给定的分钟数内可以进行的最大请求数。
        // 我们限定了这两个动作访问频率是 1 分钟内不能超过 6 次。
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
