<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OfficeRepository;

class OfficeController extends ApiController
{
    protected $officeRepository;

    public function __construct(OfficeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
        return $this->getData(function() {
            $this->compacts['items'] = $this->repository->getData();
        });
    }
}
