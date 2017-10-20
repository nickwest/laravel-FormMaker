<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;

use Nickwest\FormMaker\Attributes;

class Field{

    /**
     * Field Attributes (defaults are set in constructor)
     *
     * @var \Nickwest\FormMaker\Attributes
     */
    protected $attributes = null;

    /**
     * Human readable formatted name
     *
     * @var string
     */
    protected $label = '';

    /**
     * Suffix for every label (typically ":")
     *
     * @var string
     */
    protected $label_suffix = '';

    /**
     * An example to show by the field
     *
     * @var string
     */
    protected $example = '';

    /**
     * A default value (prepopulated if field is blank)
     *
     * @var string
     */
    protected $default_value = '';

    /**
     * The values when the field allows multiples
     *
     * @var array
     */
    protected $multi_value = [];

    /**
     * Error message to show on the field
     *
     * @var string
     */
    protected $error_message = '';

    /**
     * Blade data to pass through to the subform
     *
     * @var array
     */
    protected $subform_data = [];

    /**
     * Options to that are disabled inside of a radio, checkbox or other multi-option field
     *
     * @var array
     */
    protected $disabled_options = [];

    /**
     * A set key to use if this is a multi field
     *
     * @var string
     */
    protected $multi_key;

    /**
     * A note to display below the field (Accepts HTML markup)
     *
     * @var string
     */
    protected $note;

    /**
     * The template that this field should use
     *
     * @var string
     */
    protected $template = '';

    /**
     * Original name when field created
     *
     * @var string
     */
    protected $original_name = '';

    /**
     * Original id when field created
     *
     * @var string
     */
    protected $original_id = '';

    /**
     * Options to populate select, radio, checkbox, and other multi-option fields
     *
     * @var array
     */
    protected $options;

    /**
     * Class(es) for the field's containing div
     *
     * @var string
     */
    protected $container_class = '';

    /**
     * Class(es) for the field's label
     *
     * @var string
     */
    protected $label_class = '';

    /**
     * Class(es) for the input wrapper
     *
     * @var string
     */
    protected $input_wrapper_class = '';

    /**
     * Class(es) for the field's containing div
     *
     * @var string
     */
    protected $options_container_class = '';

    /**
     * Class(es) for the field's containing div
     *
     * @var string
     */
    protected $option_wrapper_class = '';

    /**
     * Class(es) for the field's containing div
     *
     * @var string
     */
    protected $option_label_class = '';

    /**
     * Class(es) for the field's containing div
     *
     * @var string
     */
    protected $Theme = '';

    /**
     * Constructor
     *
     * @param string $field_name
     * @param string $type
     * @return void
     */
    public function __construct(string $field_name, string $type = null)
    {
        $this->attributes = new Attributes();

        $this->attributes->name = $field_name;
        $this->attributes->type = $type != null ? $type : 'text';
        $this->attributes->id = 'input-'.$field_name;
        $this->attributes->class = '';

        $this->original_name = $this->attributes->name;
        $this->original_id = $this->attributes->id;
        $this->label = $this->makeLabel();

        // Options for multi-choice fields
        $this->options = [];
        $this->subform_data = [];
    }


//// ACCESSORS AND MUTATORS

    /**
     * Field property and attribute accessor
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if($property == 'view_namespace')
        {
            return $this->Theme->view_namespace();
        }

        if(property_exists(__CLASS__, $property))
        {
            return $this->{$property};
        }

        if($this->attributes->isValidAttribute($property))
        {
            return $this->attributes->$property;
        }

        return null;
    }

    /**
     * Field property and attribute mutator
     *
     * @param string $property
     * @param mixed $value
     * @return void
     * @throws \Exception
     */
    public function __set(string $property, $value)
    {
        if($property == 'options')
        {
            throw new \Exception('Options must be set with setOption and setOptions methods');
        }

        if(property_exists(__CLASS__, $property))
        {
            $this->{$property} = $value;
            return;
        }

        // Whenever setting value, also record the value to $this->multi_value
        if($property == 'value')
        {
            $this->multi_value = is_array($value) ? $value : [$value];
        }

        if($this->attributes->isValidAttribute($property))
        {
            $this->attributes->$property = $value;
            return;
        }

        throw new \Exception('"'.$property.'" is not a valid property.');
    }

    /**
     * Get the template that this field should use
     *
     * @return string
     */
    public function getTemplate()
    {
        // Use an override template if set
        if($this->template)
        {
            return $this->template;
        }

        // If this is a radio or checkbox switch between multiples or single
        if($this->attributes->type == 'checkbox' && is_array($this->options))
        {
            if($this->view_namespace != '' && View::exists($this->view_namespace.'::fields.checkboxes'))
            {
                return $this->view_namespace.'::fields.checkboxes';
            }
            return 'form-maker::fields.checkboxes';
        }

        // If this is a radio or checkbox switch between multiples or single
        if($this->attributes->type == 'radio' && is_array($this->options))
        {
            if($this->view_namespace != '' && View::exists($this->view_namespace.'::fields.radios'))
            {
                return $this->view_namespace.'::fields.radios';
            }
            return 'form-maker::fields.radios';
        }

        if($this->view_namespace != '' && View::exists($this->view_namespace.'::fields.'.$this->attributes->type))
        {
            return $this->view_namespace.'::fields.'.$this->attributes->type;
        }
        return 'form-maker::fields.'.$this->attributes->type;
    }

    /**
     * Set a Field attribute
     *
     * @param string $property
     * @param string $value
     * @return void
     */
    public function setAttribute(string $attribute, string $value)
    {
        // TODO add validation
        $this->attributes->attribute = $value;
    }

    /**
     * Get an attribute of the Field
     *
     * @param string $attribute
     * @return string
     * @throws \Exception
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes->attribute;
    }

    /**
     * Add a css class to the attributes
     *
     * @param string $class
     * @return void
     */
    public function addClass(string $class)
    {
        if(trim($class) != '')
        {
            $this->attributes->addClass($class);
        }
    }

    /**
     * Remove a cs  class to the attributes
     *
     * @param string $class
     * @return void
     */
    public function removeClass(string $class)
    {
        $this->attributes->removeClass($class);
    }

    /**
     * Add, change, or remove an option
     *
     * @param string $key
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function setOption(string $key, string $value)
    {
        if($value == null)
        {
            unset($this->options[$key]);
            return;
        }

        if(is_array($value) || is_object($value) || is_resource($value))
        {
            throw new \Exception('Option values must text');
        }

        $this->options[$key] = $value;
    }

    /**
     * Set options replacing all current options with those in the given array
     *
     * @param mixed $options
     * @return void
     * @throws \Exception
     */
    public function setOptions($options)
    {
        if($options == null)
        {
            $this->options = [];
            return;
        }

        if(!is_array($options))
        {
            throw new \Exception('$options must be an array or null');
        }

        foreach($options as $key => $value)
        {
            if(is_array($value) || is_object($value) || is_resource($value))
            {
                throw new \Exception('Option values must text');
            }

            $this->options[$key] = $value;
        }
    }

    /**
     * Return the formatted value of the Field's value
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->formatValue($this->value);
    }


//// View Methods


    /**
     * Make a form view for this field
     *
     * @return View
     */
    public function makeView()
    {
        if($this->error_message)
        {
            $this->addClass('error');
        }

        $this->Theme->prepareFieldView($this);

        return View::make($this->getTemplate(), ['Field' => $this]);
    }

    /**
     * Make a display only view for this field
     *
     * @return View
     */
    public function makeDisplayView()
    {
        $this->Theme->prepareFieldView($this);

        if($this->view_namespace != '' && View::exists($this->view_namespace.'::fields.display'))
        {
            return View::make($this->view_namespace.'::fields.display', array('Field' => $this));
        }
        return View::make('form-maker::fields.display', array('Field' => $this));
    }

    /**
     * Make an option view for this field
     *
     * @param string $key
     * @return View
     */
    public function makeOptionView($key)
    {
        $this->attributes->id = $this->original_id.'-'.$key;
        $this->attributes->value = $key;

        $this->attributes->checked = in_array($key, $this->multi_value) ? true : false;

        $this->Theme->prepareFieldView($this);

        if($this->view_namespace != '' && View::exists($this->view_namespace.'::fields.'.$this->attributes->type.'_option'))
        {
            return View::make($this->view_namespace.'::fields.'.$this->attributes->type.'_option', array('Field' => $this, 'key' => $key));
        }
        return View::make('form-maker::fields.'.$this->attributes->type.'_option', array('Field' => $this, 'key' => $key));
    }


//// HELPERS

    /**
     * Make sure the field has all required options and stuff set
     *
     * @return void
     * @throws \Exception
     */
    public function validateFieldStructure()
    {
        switch($this->attribute['type'])
        {
            // TODO: Expand on this so it's more comprehensive

            case 'select':
                if(!is_array($this->options) || count($this->options) == 0)
                {
                    throw new \Exception('Field validation error: Field "'.$this->attributes->name.'" must have options set');
                }
        }
    }

    /**
     * Make a label for the given field, uses $this->label if available, otherwises generates based on field name
     *
     * @return string
     */
    protected function makeLabel()
    {
        // If no label use the field's name, but replace _ with spaces
        if (trim($this->label) == '')
        {
            $this->label = ucfirst(str_replace('_', ' ', $this->attributes->name));
        }

        return $this->label;
    }

    /**
     * Return the formatted value of the $value
     *
     * @param string $value
     * @return string
     */
    protected function formatValue(string $value)
    {
        if(is_array($this->options) && isset($this->options[$value]))
        {
            return $this->options[$value];
        }

        // TODO: Add other formatting options here, specifically for dates

        return $value;
    }

}
