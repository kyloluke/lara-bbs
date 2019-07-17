<?php

namespace App\Http\Requests;

class TopicRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method) {
            case 'POST';
            case 'PUT';
            case 'PATCH';
            {
                return [
                    'title' => 'required|min:2',
                    'body' => 'required|min:3',
                    'category_id' => 'required|exists:categories,id,numeric'
                ];
            }

            case 'GET';
            case 'DELETE';
            default:
            {
                return [];
            }
        }
    }


    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '内容',
            'category_id' => '分类'
        ];
    }
}
