<?php

namespace App\Http\Requests;

use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class TemplateImageRequest extends FormRequest
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
                        return [
                            'title' => ['required','unique:templates,title'],
                            'image' => ['required'],
                            'image.id' => ['required','exists:images,id'],
                            'image.name' => ['required'],
                            'image.type' => ['required'],
                            'image.priority' => ['required',"numeric"],
                            'image.priority_type' => ['required'],
                        ];
                    } break;
    
                case 'PUT':{
                        return [];
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
