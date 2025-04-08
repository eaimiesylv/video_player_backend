<?php

namespace App\Services\Videos\CategoryService;

use App\Services\Videos\CategoryService\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index($request)
    {
        return $this->categoryRepository->index($request);
    }

    public function show($id)
    {
        return $this->categoryRepository->show($id);
    }

    public function store($data)
    {
        return $this->categoryRepository->store($data);
    }

    public function update($data, $id)
    {
        return $this->categoryRepository->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->categoryRepository->destroy($id);
    }
}
