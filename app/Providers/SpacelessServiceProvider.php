<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SpacelessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('removespace', function ($expression) {
//            error_log($expression);
//            Log::info("Hello World");
            /*            return "<?php echo " . str_replace(' ', '', $expression) . " ?>";*/
            /*            return "<?php echo 'Aduhdek' ?>";*/
            list($greet, $name) = explode(', ', $expression);

//            $greet = str_replace(' ', '', $greet);
//            $greet = $greet . "ddddd";

            return "<?php echo {$greet} . ' ' . {$name}; ?>";
        });

//        Blade::directive('endremovespace', function () {
        /*            return "<?php } ?>";*/
//        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
