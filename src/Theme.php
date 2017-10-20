<?php namespace Nickwest\FormMaker;

abstract class Theme
{
	abstract public function view_namespace(): string;

	public function __get($key)
	{
		if($key == 'view_namespace')
		{
			return $this->view_namespace();
		}
	}
}
