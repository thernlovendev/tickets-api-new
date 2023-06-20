<?php

namespace App\Http\Requests;

use App\Models\NavigationMenu;
use App\Models\NavigationSubMenu;
use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class NavigationSubMenuRequest extends FormRequest
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
        $navigation_menu_ids = NavigationMenu::pluck('id')->toArray();
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'navigation_menu_id' => ['required', Rule::in($navigation_menu_ids)],
                        'navigation_submenus' => ['required'],
                        'navigation_submenus.*.name' => ['required'],
                        'navigation_submenus.*.url' => ['string', 'required_without:navigation_submenus.*.template_id'],
                        'navigation_submenus.*.template_id' => ['integer', 'required_without:navigation_submenus.*.url', Rule::in($template_ids)],
                    ];
                } break;

            case 'PUT':{
                $navigation_submenu_ids = NavigationSubMenu::pluck('id')->toArray();
                return [
                    'navigation_menu_id' => ['required', Rule::in($navigation_menu_ids)],
                    'navigation_submenus' => ['required'],
                    'navigation_submenus.*.id' => ['required', Rule::in($navigation_submenu_ids)],
                    'navigation_submenus.*.name' => ['required'],
                    'navigation_submenus.*.url' => ['string', 'required_without:navigation_submenus.*.template_id'],
                    'navigation_submenus.*.template_id' => ['integer', 'required_without:navigation_submenus.*.url', Rule::in($template_ids)],
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
