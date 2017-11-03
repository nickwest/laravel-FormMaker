<?php namespace Nickwest\FormMaker;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class FormMakerServiceProvider extends ServiceProvider {

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
        $this->loadViewsFrom(__DIR__.'/views', 'form-maker');

        Blade::directive('formmaker_include', function($expression) {
            // $expression = self::fixExpression($expression);

            if(strpos($expression, ',') !== false)
            {
                $view = substr($expression, 0, strpos($expression, ','));
                $remainder = substr($expression, strpos($expression, ','));
            }
            else
            {
                $view = $expression;
                $remainder = '';
            }
            $template = substr($view, strpos($view, '::')+2);

            return '<?php if(View::exists('.$view.')){
                echo $__env->make('.$expression.', array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render();
            }else{
                echo $__env->make(\'form-maker::'.$template.$remainder.', array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render();
            } ?>';


            return "<?php echo \$__env->make({$expression}, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
        });

        Blade::directive('formmaker_component', function($expression) {
            $expression = self::fixExpression($expression);

            return "<?php \$__env->startComponent({$expression}); ?>";
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

    /**
     * Adjust a blade directive's expression for supporting fallback template namespaces
     *
     * @param string $expression
     * @return string
     */
    static public function fixExpression(string $expression) : string
    {
        if(strpos($expression, ',') !== false)
        {
            $view = str_replace('\'', '', substr($expression, 0, strpos($expression, ',')-1));
            $remainder = substr($expression, strpos($expression, ','));
        }
        else
        {
            $view = str_replace('\'', '', $expression);
            $remainder = '';
        }
        $template = substr($view, strpos($view, '::')+2);

        if(!View::exists($view)){
            $view = 'form-maker::'.$template;
        }

        $expression = '\''.$view.'\''.$remainder;

        return $expression;
    }

}
