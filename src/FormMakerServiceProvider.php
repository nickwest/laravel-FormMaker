<?php

namespace Nickwest\FormMaker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class FormMakerServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'form-maker');

        Blade::directive('formmaker_include', function ($expression) {
            if (strpos($expression, ',') !== false) {
                $view = substr($expression, 0, strpos($expression, ','));
                $remainder = substr($expression, strpos($expression, ','));
            } else {
                $view = $expression;
                $remainder = '';
            }
            $template = substr($view, strpos($view, '::') + 2);

            return '<?php if(View::exists(' . $view . ')){
                echo $__env->make(' . $expression . ', array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render();
            }else{
                echo $__env->make(\'form-maker::' . $template . $remainder . ', array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render();
            } ?>';
        });

        Blade::directive('formmaker_component', function ($expression) {
            if (strpos($expression, ',') !== false) {
                $view = substr($expression, 0, strpos($expression, ','));
                $remainder = substr($expression, strpos($expression, ','));
            } else {
                $view = $expression;
                $remainder = '';
            }
            $template = substr($view, strpos($view, '::') + 2);

            return '<?php if(View::exists(' . $view . ')){
                $__env->startComponent(' . $expression . ');
            }else{
                $__env->startComponent(\'form-maker::' . $template . $remainder . ');
            } ?>';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
