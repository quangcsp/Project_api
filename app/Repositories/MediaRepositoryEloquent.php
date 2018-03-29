<?php

namespace App\Repositories;

use App\Contracts\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Repositories\UploadableTrait;
use App\Eloquent\Media;
use App\Eloquent\UpdateMedia;
use App\Eloquent\Book;
use App\Eloquent\UpdateBook;

class MediaRepositoryEloquent extends AbstractRepositoryEloquent implements MediaRepository
{
    use UploadableTrait;

    public function model()
    {
        return new Media;
    }

    public function uploadAndSaveMedias(Model $relation, array $files, $path)
    {
        if (isset($files) && count($files)) {
            foreach ($files as $file) {
                $dataFile[] = [
                    'name' => $file['file']->getClientOriginalName(),
                    'size' => $file['file']->getSize(),
                    'type' => $file['type'],
                    'path' => $this->uploadFile($file['file'], $path, 'image')
                ];
            }

            if (isset($dataFile)) {
                $relation->media()->createMany($dataFile);
            }
        }
    }

    public function updateMedias(Model $relation, array $files, $path)
    {
        if (isset($files) && count($files)) {
            foreach ($files as $file) {
                $dataFile[] = [
                    'name' => $file['file']->getClientOriginalName(),
                    'size' => $file['file']->getSize(),
                    'path' => $this->uploadFile($file['file'], $path, 'image')
                ];

                if (isset($dataFile)) {
                    $this->destroyFile($relation->media()->find($file['id'])->path);
                    $relation->media()->find($file['id'])->update($dataFile[0]);
                }

                $dataFile[] = [];
            }
        }
    }

    public function uploadAndSaveEditMedias(Model $relation, array $files, $path)
    {
        if (isset($files) && count($files)) {
            foreach ($files as $file) {
                $dataFile[] = [
                    'name' => $file['file']->getClientOriginalName(),
                    'size' => $file['file']->getSize(),
                    'type' => $file['type'],
                    'path' => $this->uploadFile($file['file'], $path, 'image')
                ];
            }

            if (isset($dataFile)) {
                $relation->updateMedia()->createMany($dataFile);
            }
        }
    }

    public function updateEditMedias(Model $relation, array $files, $path)
    {
        if (isset($files) && count($files)) {
            foreach ($files as $file) {
                $dataFile[] = [
                    'media_id' => $file['id'],
                    'name' => $file['file']->getClientOriginalName(),
                    'size' => $file['file']->getSize(),
                    'type' => $file['type'],
                    'path' => $this->uploadFile($file['file'], $path, 'image')
                ];
            }

            if (isset($dataFile)) {
                $relation->updateMedia()->createMany($dataFile);
            }
        }
    }

}
