<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'fullname' => ['nullable','max:55'],
                        'firstname' => ['required','max:55'],
                        'lastname' => ['required','max:55'],
                        'email' => ['required','email','unique:users,email','max:255','confirmed'],
                        'email_confirmation' => ['required','email','max:255'],
                        'password' => ['required','max:55', 'confirmed'],
                        'password_confirmation' => ['required','max:55'],
                        'phone' => ['required','max:15']
                    ];
                } break;

            case 'PUT':{
                return [
                    'name' => ['required','max:55'],
                    'email' => ['required','email','unique:users,email','max:55'],
                    'phone' => ['required','max:15']
                ];
            } break;

            case 'DELETE': break;
            default:
            {
                return [];
            } break;
        }
    }
}
