# Critical Laravel Routes

Automatically create and link Critical CSS for static routes.

Supports Laravel 7.*

## Installation

Just require the package via composer:

```
composer require campaigningbureau/critical-laravel-routes --dev
```

The package gets automatically discovered by Laravel.

## Usage

1. All static routes, that should use critical css, need to be named and have a `critical` attribute set to `true`:
   ```
   Route::get('/', ['as' => 'index', 'uses' => 'ViewController@index', 'critical'=>'index']);
   ```

2. Generate the JSON that contains all critical routes:
   ```shell
   php artisan create:critical-urls
   ```

3. The generated file needs to be required in the webpack.mix.js and set as `urls` when creating the critical css:
   ```javascript
   // urls that need to be passed for critical css
   const criticalUrls = require('./critical-routes.json');
   ...
   mix.criticalCss({
       enabled: mix.inProduction(),
       paths: {
           base: 'http://your-website.com',
           templates: 'public/css/'
       },
       urls: criticalUrls,
       options: {
           minify: true,
       },
   });
   ```
   When generating the css, one css file per defined route is created and saved.

4. Automatically import the critical css (if it exists for the route) in the blade template:

   ```
   {!! \CriticalLaravelRoutes::linkCriticalCss() !!}
   ```

   This can be used safely vor every existing route, so it is best to put it in the base layout. If no critical css file
   is found for a route, nothing is displayed.

## Configuration

The config can be changed by publishing the configuration file:

```shell
$ php artisan vendor:publish --provider="CampaigningBureau\CriticalLaravelRoutes\CriticalLaravelRoutesServiceProvider"
```

It is possible to configure the name of the generated JSON file and the suffix for the critical css filess.