<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'start_time' => 'required|before:end_time',
            'end_time' => 'required',
            'break_times.*.start_time' => 'nullable|before:break_times.*.end_time',
            'break_times.*.end_time' => 'nullable|after:break_times.*.start_time|before:end_time',
            'remarks' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'start_time.required' => '出勤時間は必須です。',
            'start_time.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'end_time.required' => '退勤時間は必須です。',
            'break_times.*.start_time.before' => '休憩開始時間が不適切です。',
            'break_times.*.end_time.after' => '休憩終了時間が開始時間より前になっています。',
            'break_times.*.end_time.before' => '休憩終了時間が退勤時間より遅くなっています。',
            'remarks.required' => "備考を記入してください"
        ];
    }
}
