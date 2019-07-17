<?php

namespace App\Http\Requests;


class ReplyRequest extends FormRequest
{

    public function rules()
    {
        return [
            'content' => 'required|min:3',
        ];
    }
}
