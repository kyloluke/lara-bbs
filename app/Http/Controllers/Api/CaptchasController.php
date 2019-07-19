<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    /**
     * @param phone
     * @return array
     */
    public function store(CaptchaRequest $request)
    {
        $captchaBuilder = new CaptchaBuilder(4);
        $captchaBuilder = $captchaBuilder->build(100); // 100 参数表示图片的大小

        $phone = $request->phone;
        $key = 'captcha-' . str_random(15);
        $expiredAt = now()->addMinutes(2)->toDateTimeString();

        \Cache::put($key, ['phone' => $phone, 'code' => $captchaBuilder->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt,
            'captcha_code'=>$captchaBuilder->getPhrase(),    // 方便测试，返回验证码
            'captcha_image_content' => $captchaBuilder->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
