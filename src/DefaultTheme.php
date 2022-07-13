<?php

namespace Nickwest\FormMaker;

use Nickwest\FormMaker\Theme;

class DefaultTheme extends Theme
{
	public function view_namespace(): string
	{
		return 'form-maker';
	}
}
