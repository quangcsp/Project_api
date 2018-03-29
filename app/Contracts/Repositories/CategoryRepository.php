<?php

namespace App\Contracts\Repositories;

interface CategoryRepository extends AbstractRepository
{
    public function getData($data = [], $with = [], $dataSelect = ['*']);
}
