<?php

namespace App\Support;

class UserListCacheKey
{
    public static function make(array $params): string
    {
        // Version allows cheap invalidation on create/update.
        $version = cache()->get('users:list:version', 1);

        ksort($params); // Sorting it to stable the data.
        return 'users:list:v'.$version.':'.sha1(json_encode($params));
    }
}
