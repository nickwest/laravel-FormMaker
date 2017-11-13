<?php
namespace Nickwest\FormMaker\CustomFields\daysofweek;

use Nickwest\FormMaker\CustomField as BaseCustomField;
use Illuminate\Support\Facades\View;

class CustomField extends BaseCustomField
{
    /**
     * The Days of the week that we use for storing daysofweek fields
     *
     * @var array
     */
    public $daysofweek = [ 'M' => 'Mon', 'T' => 'Tue', 'W' => 'Wed', 'R' => 'Thu', 'F' => 'Fri', 'S' => 'Sat', 'U' => 'Sun' ];

    public function makeView(\Nickwest\FormMaker\Field $Field)
    {
        return View::make('form-maker::customfields.daysofweek', ['Field' => $Field, 'daysofweek' => $this->daysofweek]);
    }

    public function hook_setAllFormValues(\Nickwest\FormMaker\Field $Field, $value)
    {
        $data = explode('|', $value);
        foreach($this->daysofweek as $key => $day) {
            if(in_array($key, $data)) {
                $return[$key] = 1;
            } else {
                $return[$key] = 0;
            }
        }
        return $return;
    }

    public function hook_setPostValues($value)
    {
        return implode('|',$value);
    }


}
