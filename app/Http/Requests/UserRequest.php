<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[a-zA-Z0-9\_\-]+$/|unique:users,name,'.Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:png,jpeg,bmp,gif|dimensions:min_width=200,min_height=200'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'email' => '邮箱名',
            'introduction' => '个人简介',
            'avatar' => '头像'
        ];
    }
}
