<?php

namespace App\Contracts\Repositories;

interface OfficeRepository extends AbstractRepository
{
    public function getData($data = [], $with = [], $dataSelect = ['*']);
}
