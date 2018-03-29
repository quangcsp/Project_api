<?php

namespace App\Events;

use App\Eloquent\Book;
use App\Traits\Repositories\UploadableTrait;

class DeleteBookHandler
{
    use UploadableTrait;

    protected $book;

    public function handle(Book $book)
    {
        if ($book) {
            $this->book = $book;

            foreach ($this->book->media as $media) {
                $this->destroyFile($media->path);
            }

            $this->book->media()->delete();
        }
    }
}
