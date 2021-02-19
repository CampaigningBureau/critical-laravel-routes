<?php


namespace CampaigningBureau\CriticalLaravelRoutes;


class Manager
{
    /**
     * inline the content of the critical css file
     *
     * @return string|void
     */
    public function inlineCriticalCss()
    {
        $match = $this->getCriticalCssFile();

        if (!$match) {
            return;
        }

        // if the css with the given name exists, return the content
        if (file_exists(public_path('css/' . $match->template . config('critical-laravel-routes.suffix') . '.css'))) {
            return '<style>' .
                   file_get_contents(public_path('css/' . $match->template . config('critical-laravel-routes.suffix') .
                                                 '.css')) . '</style>';
        }
    }

    /**
     * link the critical css file if it exists
     * @return string|void
     */
    public function linkCriticalCss()
    {
        $match = $this->getCriticalCssFile();

        if (!$match) {
            return;
        }

        // if the css with the given name exists, return it
        if (file_exists(public_path('css/' . $match->template . config('critical-laravel-routes.suffix') . '.css'))) {
            return '<link
            href="' . asset('css/' . $match->template . config('critical-laravel-routes.suffix') . '.css') . '"
            rel="stylesheet"
            >';
        }
    }

    /**
     * @return false|mixed
     */
    private function getCriticalCssFile()
    {
        $currentUri = \Route::current()->uri();
        $currentPath = strncmp($currentUri, '/', 1) === 0 ? $currentUri : '/' .
                                                                          $currentUri;
        // check  if the critical routes file exists and can be parsed
        if (!file_exists(base_path(config('critical-laravel-routes.file-name')))) {
            return false;
        }

        try {
            $urlsFile = json_decode(file_get_contents(base_path(config('critical-laravel-routes.file-name'))), false);
        } catch (\ErrorException $e) {
            return false;
        }

        // find the current path inside the json
        $match = collect($urlsFile)->first(function ($criticalUrl, $key) use ($currentPath)
        {
            return $criticalUrl->url === $currentPath;
        });

        return $match ?? false;
    }
}