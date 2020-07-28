<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroup extends FormRequest
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
        //       Best : 신청 시작 ->         신청 끝
//                      행사 시작 ->        행사 끝
//                                         행사 시작 -> 행사 끝
//       특이 케이스: 행사 중에 신청을 받는 경우. -> 있을 수도 있는 상황.
//         행사 끝나고 신청을 받기 시작 하는 경우 -> 안되게 처리.

        return [
            'group_name' => ['required'],
            'apply_start_date' => ['required'],
            'apply_end_date' => ['required', 'after_or_equal:apply_start_date'],
            'action_start_date' => ['required'],
            'action_end_date' => ['required', 'after_or_equal:action_start_date', 'after_or_equal:apply_start_date'],
            'capacity' => ['required', 'max:999', 'min:1']
        ];
    }
}
