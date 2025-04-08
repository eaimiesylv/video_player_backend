<?php

namespace App\Http\Controllers\Videos;

use App\Http\Controllers\Controller;
use App\Services\Videos\VideoService\VideoService;
use App\Http\Requests\Videos\VideoFormRequest;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    private $videoService;
    protected $fileUploadService;

    public function __construct(VideoService $videoService, FileUploadService $fileUploadService)
    {
        $this->videoService = $videoService;
        $this->fileUploadService = $fileUploadService;
    }

    public function index()
    {
        return $this->videoService->index();
    }

    public function show($id)
    {
        return $this->videoService->show($id);
    }

    public function store(VideoFormRequest $request)
    {
        $validated = $request->validated();

        // Check if file is uploaded and handle image upload
        if ($request->hasFile('video_url')) {
            $validated['video_url'] = $this->fileUploadService->uploadImage($request->file('video_url'), 'video');
        }

        return $this->videoService->store($validated);
    }

    public function update(VideoFormRequest $request, $id)
    {
        return $this->videoService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->videoService->destroy($id);
    }
}
