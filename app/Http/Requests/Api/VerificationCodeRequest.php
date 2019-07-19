<?php

namespace App\Http\Requests\Api;


class VerificationCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'captcha_key' => 'required',
            'captcha_code' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '图片验证码key',
            'captcha_code' => '图片验证码'
        ];
    }
}
