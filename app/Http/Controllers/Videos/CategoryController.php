<?php

namespace App\Http\Controllers\Videos;

use App\Http\Controllers\Controller;
use App\Services\Videos\CategoryService\CategoryService;
use App\Http\Requests\Videos\CategoryFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return $this->categoryService->index($request);
    }

    public function show($id)
    {
        return $this->categoryService->show($id);
    }

    public function store(CategoryFormRequest $request)
    {
        return $this->categoryService->store($request->all());
    }

    public function update(CategoryFormRequest $request, $id)
    {
        return $this->categoryService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }
}
