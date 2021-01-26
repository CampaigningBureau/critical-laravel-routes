<?php


namespace CampaigningBureau\CriticalLaravelRoutes\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;

class GenerateUrls extends Command
{
    private $criticalRoutes = [];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'critical-urls:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a json file with the URLs of all defined routes that contain the "critical" action.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->criticalRoutes = collect(\Route::getRoutes()->getRoutes())->reduce(function ($carry, Route $route)
        {
            if ($route->getAction('critical', false)) {
                // build urls with prepended /
                $carry[] = [
                    'url'      => strncmp($route->uri, '/', 1) === 0 ? $route->uri : '/' . $route->uri,
                    'template' => $route->getName(),
                ];
            }

            return $carry;
        });


        \File::put(config('critical-laravel-routes.file-name'), json_encode($this->criticalRoutes));

        $this->info('The file has been saved to \'' . config('critical-laravel-routes.file-name') . '\'');
    }
}