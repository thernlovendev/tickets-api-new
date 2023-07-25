<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
                        'company_id' => ['required','exists:companies,id'],
                        'cities' =>[
                            'required'
                        ],
                        'cities.*.name'=>['required',Rule::unique('cities')->
                        where(fn ($query) => $query->where('company_id',$this->company_id)), 'distinct'],
                    ];
                } break;

            case 'PUT':{
                $city = $this->route('city');
                return [
                    'company_id' => ['required','exists:companies,id'],
                    'name'=> ['required',
                    Rule::unique('cities')->where(fn ($query) => $query->where('company_id',$this->company_id))->ignore($city->id)]
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
