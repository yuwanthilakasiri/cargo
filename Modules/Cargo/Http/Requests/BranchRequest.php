<?php

namespace Modules\Cargo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cargo\Entities\Branch;

class BranchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email_validation = 'required|max:50|email|unique:users,email';
        $password_validation = 'string|min:6';
        if ($this->method() == 'PUT') {
            $user_id = Branch::where('id',$this->route('branch'))->pluck('user_id')->first();
            $email_validation .= (',' . $user_id . 'id');
            $password_validation = 'nullable|' . $password_validation;
        } else {
            $password_validation = 'required|' . $password_validation;
        }
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => $email_validation,
            'password' => $password_validation,
            'responsible_mobile' => 'required|min:5 ',
            'country_code' => 'required',
            'responsible_name' => 'required|string|min:3|max:50',
            'national_id'   => 'required',
            'address' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}