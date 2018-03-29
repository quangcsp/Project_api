<?php

namespace App\Repositories;

use App\Contracts\Repositories\OfficeRepository;
use App\Eloquent\Office;

class OfficeRepositoryEloquent extends AbstractRepositoryEloquent implements OfficeRepository
{
    public function model()
    {
        return new Office;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        $categories = $this->model()
            ->select($dataSelect)
            ->with($with)
            ->get();

        return $categories;
    }
}
