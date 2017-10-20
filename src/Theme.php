<?php namespace Nickwest\FormMaker;

abstract class Theme
{
    abstract public function view_namespace() : string;

    public function __get($key)
    {
        if($key == 'view_namespace')
        {
            return $this->view_namespace();
        }
    }

    /**
     * Modify a field as necessary
     *
     * @return void
     */
    public function prepareFieldView(\Nickwest\FormMaker\Field &$Field)
    {
        return;
    }

    /**
     * Modify a field as necessary
     *
     * @return void
     */
    public function prepareFieldOptionView(\Nickwest\FormMaker\Field &$Field)
    {
        return;
    }

    /**
     * Modify a field as necessary
     *
     * @return void
     */
    public function prepareFormView(\Nickwest\FormMaker\Field &$Field)
    {
        return;
    }

}
