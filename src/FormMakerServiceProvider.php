<?php namespace Nickwest\FormMaker;

use Illuminate\Support\ServiceProvider;
use Form;

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
		//$this->package('nickwest/form-maker');
		
		// Create a rawLabel macro for the Illuminate\Html\Form class
		Form::Macro('rawLabel', function($name, $value = null, $options = array()){
		    $label = Form::label($name, '%s', $options);
		
		    return sprintf($label, $value);
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
		return array();
	}

}
