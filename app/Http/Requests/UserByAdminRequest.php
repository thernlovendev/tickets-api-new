<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserByAdminRequest extends FormRequest
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
                    $request = $this;
                    return [
                        'fullname' => ['required','max:55'],
                        'firstname' => ['required','max:55'],
                        'lastname' => ['required','max:55'],
                        'email' => ['unique:users,email,NULL,id,company_id,'.$request->input('company_id'),'required','email','max:255','confirmed'],
                        'email_confirmation' => ['required','email','max:255'],
                        'password' => ['required','max:55', 'confirmed'],
                        'password_confirmation' => ['required','max:55'],
                        'phone' => ['required','max:15'],
                        'role' => ['required','exists:roles,id'],
                        'company_id' => ['required','exists:users,id']
                    ];
                } break;

            case 'PUT':{
                $user = $this->route('user');
                return [
                    'fullname' => ['required','max:55'],
                    'firstname' => ['required','max:55'],
                    'lastname' => ['required','max:55'],
                    'email' => ['required',Rule::unique('users')->where('company_id',$user->company_id)->ignore($user->email),'email','max:255','confirmed'],
                    'email_confirmation' => ['required','email','max:255'],
                    'password' => ['nullable','max:55', 'confirmed'],
                    'password_confirmation' => ['nullable','max:55'],
                    'phone' => ['required','max:15'],
                    'role' => ['required','exists:roles,id'],
                    'company_id' => ['required']
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
