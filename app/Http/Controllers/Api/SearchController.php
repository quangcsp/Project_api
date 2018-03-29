<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\GoogleBookInterface;
use App\Http\Requests\Api\Search\GoogleBookRequest;
use App\Contracts\Services\CounterInterface;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function search(GoogleBookRequest $request, GoogleBookInterface $service)
    {
        $data = $request->only([
            'title', 'inauthor', 'subject', 'q', 'maxResults'
        ]);

        return $this->requestAction(function() use ($data, $service) {
            $this->compacts['items'] = $service->search($data);
        });
    }

    public function detail(Request $request, GoogleBookInterface $service)
    {
        $bookId = $request->book_id;

        return $this->requestAction(function() use ($bookId, $service) {
            $this->compacts['item'] = $service->detail($bookId);
        });
    }
}
