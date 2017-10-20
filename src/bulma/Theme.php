<?php namespace Nickwest\FormMaker\bulma;

class Theme extends \Nickwest\FormMaker\Theme
{
    public function view_namespace() : string
    {
        return 'form-maker-bulma';
    }

    public function prepareFieldView(\Nickwest\FormMaker\Field &$Field)
    {
        $Field->label_class = 'label';

        switch($Field->type)
        {
            case 'text':
            case 'email':
            case 'tel':
            case 'url':
            case 'password':
                $Field->addClass('input');
            break;
            case 'file':
                $Field->addClass('file-input');
            break;
            case 'select':
                $Field->input_wrapper_class = 'select';
            break;

            // These are less than perfect, but Bulma doesn't have unique style for them yet.
            case 'number':
            case 'date':
            case 'month':
            case 'time':
            case 'week':
            case 'color':
                $Field->addClass('input');
            break;
        }

        return;
    }

}
