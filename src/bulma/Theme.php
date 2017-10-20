<?php namespace Nickwest\FormMaker\bulma;

class Theme extends \Nickwest\FormMaker\Theme
{
    public function view_namespace() : string
    {
        return 'form-maker-bulma';
    }

    public function prepareFieldView(\Nickwest\FormMaker\Field &$Field)
    {
        return;
    }

}
