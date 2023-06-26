<?php

namespace App\Http\Requests;

use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class TemplateRequest extends FormRequest
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
        $status = Template::STATUS;
        $type = Template::TYPE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {

                    if($this->type ==$type['EMAIL']){
                        return [
                            'email' => 'required'
                        ];
                    }else{
                        return [
                            'title' => ['required','unique:templates,title'],
                            'type' => ['required', Rule::in($type)],
                            'header_gallery_id' => ['required', 'exists:header_galleries,id'],
                            'content' => ['required'],
                            'status' => ['required', Rule::in($status)],
                            'created_by' => ['required'],
                        ];
                    };
                } break;

            case 'PUT':{
                $template = $this->route('template');
                if($template->type == 'Email'){
                    return[
                        'subject' => ['required'],
                        'content' => ['required'],
                    ];
                } else {
                    return [
                        'title' => ['required',Rule::unique('templates')->ignore($template->id)],
                        'type' => ['required'],
                        'header_gallery_id' => ['required', 'exists:header_galleries,id'],
                        'content' => ['required'],
                        'status' => ['required', Rule::in($status)],
                        'created_by' => ['required'],
                    ];
                }
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

    public function messages()
    {
        return [
            'email.required' => 'Not allowed to create a new template for email',
        ];
    }
}
