<?php

namespace App\Http\Requests;

use App\Models\Template;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class NavigationMenuRequest extends FormRequest
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
                        'name' => ['required'],
                        'url' => ['string'],
                        'template_id' => ['integer_or_nullable'],
                        'ticket_id' => ['integer_or_nullable'],
                    ];
                } break;

            case 'PUT':{
                $navigation_menu_id = $this->route('navigation_menu');
                return [
                    'name' => ['required',Rule::unique('navigation_menus')->ignore($navigation_menu_id),],
                    'url' => ['string'],
                    'template_id' => ['integer_or_nullable'],
                    'ticket_id' => ['integer_or_nullable'],
                ];
            } break;

            case 'DELETE': break;
            default:
            {
                return [];
            } break;
        }
    }

        /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'errors' => (new ValidationException($validator))->errors(),
            ], 
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
