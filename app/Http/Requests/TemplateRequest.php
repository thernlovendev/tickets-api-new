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
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'title' => ['required','unique:templates,title'],
                        'type' => ['required'],
                        'header_gallery_id' => ['required', 'exists:header_galleries,id'],
                        'content' => ['required'],
                        'status' => ['required', Rule::in($status)],
                        'created_by' => ['required'],
                        'header_image' => ['required'],
                        'header_image.id' => ['required','exists:images,id'],
                        // 'header_image.priority_type' => ['required',Rule::in($type_images)],
                    ];
                } break;

            case 'PUT':{
                $template = $this->route('template');
                return [
                    'title' => ['required',Rule::unique('templates')->ignore($template->id)],
                    'type' => ['required'],
                    'header_gallery_id' => ['required', 'exists:header_galleries,id'],
                    'content' => ['required'],
                    'status' => ['required', Rule::in($status)],
                    'created_by' => ['required'],
                    'header_image' => ['required'],
                    'header_image.id' => ['required','exists:images,id'],
                    // 'header_image.priority_type' => ['required',Rule::in($type_images)],
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
