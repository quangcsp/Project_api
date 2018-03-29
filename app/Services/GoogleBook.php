<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use App\Contracts\Services\GoogleBookInterface;

class GoogleBook implements GoogleBookInterface
{
    protected $client;

    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client;

        $this->apiUrl = env('GOOGLE_BOOK_URL') . '/' . env('GOOGLE_BOOK_VERSION');
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    private function getAuth()
    {
        return [
            'key' => env('GOOGLE_BOOK_KEY'),
        ];
    }

    public function request(callable $request)
    {
        try {
            $response = call_user_func($request);

            return json_decode($response->getBody());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                return json_decode($response->getBody());
            }

            throw new Exception("RequestException");
        }
    }

    private function formatResponseSearch($response)
    {
        $books = [];

        if ($response->totalItems) {
            foreach ($response->items as $book) {
                $books[] = [
                    'id' => $book->id,
                    'volumeInfo' => $book->volumeInfo,
                ];
            }
        }

        return $books;
    }

    private function formatResponseDetail($response)
    {
        if (!isset($response->error)) {
            return [
                'id' => $response->id,
                'volumeInfo' => $response->volumeInfo,
            ];
        }
    }

    private function getUrl($path, $parameters = [])
    {
        $params = array_merge($parameters, $this->getAuth());

        return $this->apiUrl . '/' . $path . '?' . http_build_query($params);
    }

    public function search(array $params)
    {
        return $this->formatResponseSearch(
            $this->request(function() use ($params) {
                return $this->client->get($this->getUrl('volumes/', $params));
            })
        );
    }

    public function detail(string $bookId)
    {
        return $this->formatResponseDetail(
            $this->request(function() use ($bookId) {
                return $this->client->get($this->getUrl("volumes/$bookId"));
            })
        );
    }
}
