<?php

namespace Nickwest\FormMaker;

use Nickwest\FormMaker\Field;


/*
    Could change this over to use Laravels Event system, but that would introduce more dependencies
*/

abstract class CustomField
{

    public abstract function makeView(Field $Field, bool $prev_inline = false);

    public function hook_setAllFormValues(Field $Field, $value)
    {
        return;
    }

    public function hook_setPostValues($value)
    {
        throw new NotImplementedException();
    }
}
