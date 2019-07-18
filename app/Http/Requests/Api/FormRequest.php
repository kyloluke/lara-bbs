<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    // 所有的验证类都会继承 Dingo 提供的 FormRequest
    public function authorize()
    {
        return true;
    }
  
}
