<?php namespace Nickwest\FormMaker;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Event;

class FormObserver {	

	/**
	 * Fire the namespaced form event
	 *
	 * @param  string $event
	 * @param  \Illuminate\Database\Eloquent\Model $model
	 * @return mixed
	 */
	protected function fireFormEvent($event, Model $model)
	{
		return Event::until('form.'.$event, [$model]);
	}

}