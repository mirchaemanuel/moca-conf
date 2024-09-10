<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

final class AvatarImage
{

    /**
     * @return SplFileInfo random file
     */
    public static function getRandomFile(): SplFileInfo
    {
        return /** @var SplFileInfo */ collect(
            File::files(database_path('seeders/local_images/speakers'))
        )->random();
    }

}
