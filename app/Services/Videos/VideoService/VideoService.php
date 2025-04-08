<?php

namespace App\Services\Videos\VideoService;

use App\Services\Videos\VideoService\VideoRepository;

class VideoService
{
    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function index()
    {
        return $this->videoRepository->index();
    }

    public function show($id)
    {
        return $this->videoRepository->show($id);
    }

    public function store($data)
    {
        return $this->videoRepository->store($data);
    }

    public function update($data, $id)
    {
        return $this->videoRepository->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->videoRepository->destroy($id);
    }
}
