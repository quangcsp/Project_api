<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CategoryRepository;
use App\Http\Requests\Api\Category\CreateCategoryRequest;
use App\Http\Requests\Api\Category\SearchRequest;

class CategoryController extends ApiController
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
        return $this->getData(function() {
            $this->compacts['items'] = $this->repository->getData();
        });
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->only(['name', 'description']);

        return $this->doAction(function() use ($data) {
            $this->compacts['items'] = $this->repository->store($data);
        });
    }

    public function update(CreateCategoryRequest $request, $categoryId)
    {
        $data = $request->only(['name', 'description']);

        return $this->doAction(function() use ($data, $categoryId) {
            $category = $this->repository->findOrFail($categoryId);

            $this->repository->update($categoryId, $data);
        });
    }

    public function getTotalCategory()
    {
        return $this->getData(function() {
            $this->compacts['item'] = $this->repository->countRecord();
        });
    }

    public function getCategoryByPage()
    {
        return $this->getData(function() {
            $data = $this->repository->getByPage();

            $this->compacts['items'] = $this->reFormatPaginate($data);
        });
    }

    public function searchCategoryByName(SearchRequest $request)
    {
        $data = $request->only(['key', 'page']);

        return $this->getData(function() use ($data) {
            $data = $this->repository->searchByName($data);

            $this->compacts['items'] = $this->reFormatPaginate($data);
        });
    }

    public function show($categoryId)
    {
        $this->compacts['item'] = $this->repository->show($categoryId);

        return $this->jsonRender();
    }
}
