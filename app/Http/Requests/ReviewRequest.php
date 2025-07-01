<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required',
            'star'       => 'required|numeric|min:1|max:5',
            'comment'    => 'required|max:255',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_id.required' => 'Sản phẩm là trường bắt buộc.',
            'star.required' => 'Số sao là trường bắt buộc.',
            'star.numeric' => 'Số sao phải là kiểu số.',
            'star.min' => 'Số sao nhỏ nhất là 1.',
            'star.max' => 'Số sao tối đa là 5.',
            'comment.required' => 'Nội dung đánh giá là trường bắt buộc.',
            'comment.max' => 'Nội dung đánh giá có tối đa 255 ký tự.',
        ];
    }
}
