<?php

namespace App\Http\Requests\Videos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VideoFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $videoId = $this->video ? $this->video->id : null;

        return [
            'video_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('videos')
                    ->where('category_id', $this->category_id)
                    ->ignore($videoId),
            ],
            'video_description' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'video_url' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/x-flv,video/x-matroska,video/webm',

        ];

    }
}
