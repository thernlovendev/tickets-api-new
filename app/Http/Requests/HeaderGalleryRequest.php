<?php

namespace App\Http\Requests;

use App\Models\HeaderGallery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class HeaderGalleryRequest extends FormRequest
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
        $type_images = HeaderGallery::TYPE_IMAGES;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'title' => ['required'],
                        'first_phrase' => ['required'],
                        'second_phrase' => ['required'],
                        'is_show' => ['required','integer'],
                        'main_image' => ['required'],
                        'main_image.id' => ['required','exists:images,id'],
                        'main_image.priority_type' => ['required',Rule::in($type_images)],
                        'galleries' => ['required'],
                        'galleries.*.id' => ['required','exists:images,id'],
                        'galleries.*.priority' => ['required'],
                        'galleries.*.priority_type' => ['required',Rule::in($type_images)],
                    ];
                } break;

            case 'PUT':{
                $header_gallery = $this->route('header_gallery');
                return [
                    'title' => ['required',Rule::unique('header_galleries')->ignore($header_gallery->id),],
                    'first_phrase' => ['required'],
                    'second_phrase' => ['required'],
                    'is_show' => ['required','integer'],
                    'main_image' => ['required'],
                    'main_image.id' => ['required','exists:images,id'],
                    'main_image.priority_type' => ['required',Rule::in($type_images)],
                    'galleries' => ['required'],
                    'galleries.*.id' => ['required','exists:images,id'],
                    'galleries.*.priority' => ['required'],
                    'galleries.*.priority_type' => ['required',Rule::in($type_images)],
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
