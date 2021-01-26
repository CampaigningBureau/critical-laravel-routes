<?php


namespace CampaigningBureau\CriticalLaravelRoutes;


class Manager
{
    /**
     * link the critical css file if it exists
     * @return string|void
     */
    public function linkCriticalCss()
    {
        $currentUri = \Route::current()->uri();
        $currentPath = strncmp($currentUri, '/', 1) === 0 ? $currentUri : '/' .
                                                                          $currentUri;

        // check  if the critical routes file exists and can be parsed
        if (!\File::exists(base_path(config('critical-laravel-routes.file-name')))) {
            return;
        }

        try {
            $urlsFile = json_decode(file_get_contents(base_path(config('critical-laravel-routes.file-name'))), false);
        } catch (\ErrorException $e) {
            return;
        }

        // find the current path inside the json
        $match = collect($urlsFile)->first(function ($criticalUrl, $key) use ($currentPath)
        {
            return $criticalUrl->url === $currentPath;
        });
        if ($match === null) {
            return;
        }

        // if the css with the given name exists, return it
        if (\File::exists(public_path('css/' . $match->template . config('critical-laravel-routes.suffix') . '.css'))) {
            return '<link
            href="' . asset('css/' . $match->template . config('critical-laravel-routes.suffix') . '.css') . '"
            rel="stylesheet"
            >';
        }
    }
}