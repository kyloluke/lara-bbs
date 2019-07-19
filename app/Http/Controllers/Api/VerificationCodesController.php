<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    /**
     * @param captcha_key
     * @param captcha_code
     * @return array
     */
    public function store(VerificationCodeRequest $request, EasySms $sms)
    {
        // 验证图片验证码
        $captchaData = \Cache::get($request->captcha_key);
        if (!$captchaData) {
            return $this->response->error('图片验证码已失效', 422);
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证失败就清除验证码缓存
            \Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误'); // 返回 401
        }
        // 验证通过清除图片验证码缓存
        \Cache::forget($request->captcha_key);

        // 发送短信验证码
        $phone = $captchaData['phone'];
        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $response = $sms->send($phone, [
                    'content' =>  '【路丁丁】您的验证码是1234。如非本人操作，请忽略本短信'
                ]);
            } catch (NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(10)->toDateTimeString();

        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt,
        ])->setStatusCode('201');
    }
}
