<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                        'city_id' => ['required','exists:cities,id'],
                        'name' => ['required',Rule::unique('categories')->
                                              where(fn ($query) => $query->where('city_id',$this->city_id)),
                                    'max:30'],
                        'subcategories' =>[
                            'nullable'
                        ],
                        'subcategories.*.name'=>['required','distinct'],
                    ];
                } break;

            case 'PUT':{
                return [
                    'name' => ['required','max:30',Rule::unique('categories')->ignore($this->route('category'))->
                    where(fn ($query) => $query->where('city_id',$this->city_id))],
                    'subcategories' =>[
                        'nullable'
                    ],
                    'subcategories.*.name'=>['required','distinct'],
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
