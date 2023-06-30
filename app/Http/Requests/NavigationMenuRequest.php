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
        $template_ids = Template::pluck('id')->toArray();
        $ticket_ids = Ticket::pluck('id')->toArray();
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'name' => ['required'],
                        'url' => ['string', 'required_without:template_id'],
                        'template_id' => ['integer', 'required_without:url', Rule::in($template_ids)],
                        'ticket_id' => ['integer', 'required', Rule::in($ticket_ids)],
                    ];
                } break;

            case 'PUT':{
                $navigation_menu_id = $this->route('navigation');
                return [
                    'name' => ['required',Rule::unique('navigation_menus')->ignore($navigation_menu_id),],
                    'url' => ['string', 'required_without:template_id'],
                    'template_id' => ['integer', 'required_without:url', Rule::in($template_ids)],
                    'ticket_id' => ['integer', 'required', Rule::in($ticket_ids)],
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
