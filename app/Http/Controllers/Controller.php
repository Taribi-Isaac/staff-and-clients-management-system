<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Headers for generated downloads so browsers (and any shared proxies) do not
     * reuse a cached body when the same URL is hit again after data changes.
     */
    protected function preventDownloadCachingHeaders(): array
    {
        return [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
    }
}
