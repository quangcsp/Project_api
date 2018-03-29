<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Eloquent\Media;

interface MediaRepository extends AbstractRepository
{
    public function uploadAndSaveMedias(Model $relation, array $medias, $path);

    public function updateMedias(Model $relation, array $medias, $path);

    public function uploadAndSaveEditMedias(Model $relation, array $files, $path);

    public function updateEditMedias(Model $relation, array $files, $path);
}
