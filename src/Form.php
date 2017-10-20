<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;

class Form{

    /**
     * Array of Field Objects
     *
     * @var array
     */
    protected $Fields = [];

    /**
     * Array of field_names to display
     *
     * @var array
     */
    protected $display_fields = [];

    /**
     * Array of field_names to display
     *
     * @var array
     */
    protected $submit_buttons = [];

    /**
     * Array of valid columns for the model using this trait
     *
     * @var array
     */
    protected $valid_columns = [];

    /**
     * Add Delete button?
     *
     * @var bool
     */
    protected $allow_delete = false;

    /**
     * Post URL
     *
     * @var string
     */
    public $url = '';

    /**
     * Form ID used as HTML attribute
     *
     * @var string
     */
    public $form_id = '';

    /**
     * Submit Button name (used for first submit button only)
     *
     * @var string
     */
    public $submit_button = null;

    /**
     * Form method (typically post or get)
     *
     * @var string
     */
    public $method = 'post';

    /**
     * Should the form accept uploads?
     *
     * @var string
     */
    public $multipart = false;

    /**
     * The Days of the week that we use for storing daysofweek fields
     *
     * @var array
     */
    protected $daysofweek = [ 'M' => 'Mon', 'T' => 'Tue', 'W' => 'Wed', 'R' => 'Thu', 'F' => 'Fri', 'S' => 'Sat', 'U' => 'Sun' ];

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Field accessor
     *
     * @param string $field_name
     * @return Field
     */
    public function __get(string $field_name)
    {
        if(isset($this->Fields[$field_name]))
        {
            return $this->Fields[$field_name];
        }

        return null;
    }

    /**
     * Field mutator
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->setProperty($key, $value);
    }

    /**
     * Field mutator
     *
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws \Exception
     */
    public function setProperty(string $key, $value)
    {
        if(isset($this->{$key}))
        {
            $this->{$key} = $value;
        }
        else
        {
            throw new \Exception('Invalid property');
        }
    }

    // public function setMultiTrue($fields)
    // {
    //     if(is_array($fields))
    //     {
    //         foreach($fields as $field)
    //         {
    //             $this->$field->multi_key = true;
    //         }
    //     }
    // }

    /**
     * Get the days of the week array
     *
     * @return array
     */
    public function getDaysOfWeekValues()
    {
        return $this->daysofweek;
    }

    /**
     * Get the Fields array
     *
     * @return array
     */
    public function getFields()
    {
        return $this->Fields;
    }

    /**
     * Get the Submit button array
     *
     * @return array
     */
    public function getSubmitButtons()
    {
        return $this->submit_buttons;
    }

    /**
     * Get the allow_delete value
     *
     * @return bool
     */
    public function getAllowDelete()
    {
        return $this->allow_delete;
    }

    /**
     * Make a view and extend $extends in section $section, $blade_data is the data array to pass to View::make()
     *
     * @param array $blade_data
     * @param string $extends
     * @param string $section
     * @return View
     */
    public function makeView(array $blade_data, string $extends = '', string $section = '')
    {
        $this->validateFormStructure();

        // foreach($this->display_fields as $field)
        // {
        //     $this->Fields[$field]->setupAttributes();
        // }

        $blade_data['Form'] = $this;
        $blade_data['extends'] = $extends;
        $blade_data['section'] = $section;

        if($extends != '')
        {
            return View::make('form-maker::form-extend', $blade_data);
        }

        return View::make('form-maker::form', $blade_data);
    }

    /**
     * Make a view, $blade_data is the data array to pass to View::make()
     *
     * @param array $blade_data
     * @return View
     */
    public function makeSubformView(array $blade_data)
    {
        $blade_data['Form'] = $this;
        $blade_data['daysofweek'] = $this->daysofweek;

        // foreach($this->display_fields as $field)
        // {
        //     $this->Fields[$field]->setupAttributes();
        // }

        return View::make('form-maker::subform', $blade_data);
    }

    /**
     * Step through fields and make sure they're legit
     *
     * @return void
     * @throws \Exception
     */
    public function validateFormStructure()
    {
        foreach($this->Fields as $Field)
        {
            $Field->validateFieldStructure();
        }

        // TODO: Add any additional Form validation here
    }

    /**
     * Add a bunch of fields to the form, New fields will overwrite old ones with the same name
     *
     * @param array $field_names
     * @return void
     */
    public function addFields(array $field_names)
    {
        foreach($field_names as $field_name)
        {
            $this->Fields[$field_name] = new Field($field_name);
        }
    }

    /**
     * Add a data list to the form
     *
     * @param array $name
     * @param array $options
     * @return void
     */
    public function addDatalist(string $name, array $options)
    {
        $this->addField($name);

        $this->{$name}->attributes->type = 'datalist';
        $this->{$name}->attributes->id = $name;
        $this->{$name}->setOptions($options);

        $this->addDisplayFields([$name]);
    }

    /**
     * Add a submit button to the form (If manually adding submit buttons, these will override the default options)
     *
     * @param string $name
     * @param string $label
     * @param string $class
     * @return void
     */
    public function addSubmitButton(string $name, string $label, string $class = '')
    {
        $this->submit_buttons[] = [
            'name' => $name,
            'label' => $label,
            'class' => $class,
        ];
    }

    /**
     * Add a Subform into the current form
     *
     * @param string $name
     * @param \Nickwest\FormMaker\Form $form
     * @param string $before_field
     * @return void
     */
    public function addSubform(string $name, \Nickwest\FormMaker\Form $Form, string $before_field = '')
    {
        $this->addField($name);
        $this->Fields[$name]->type = 'subform';
        $this->Fields[$name]->subform = $Form;

        // Insert it at a specific place in this form
        if($before_field != null)
        {
            foreach($this->display_fields as $key => $value)
            {
                if($value == $before_field)
                {
                    $this->display_fields = array_merge(array_slice($this->display_fields, 0, $key), array($name), array_slice($this->display_fields, $key));
                    return;
                }
            }
        }

        // Stick it on the end of the form
        $this->display_fields[] = $name;
    }


    /**
     * Format the value of a field
     *
     * @param string $field_name
     * @return string
     * @throws \Exception
     */
    public function formatValue(string $field_name, $value)
    {
        if(isset($this->Fields[$field_name]))
        {
            return $this->Fields[$field_name]->formatValue($value);
        }

        throw new \Exception('Field does not exist');
    }

    /**
     * Add a single field to the form
     *
     * @param string $field_name
     * @return void
     */
    public function addField(string $field_name)
    {
        $this->Fields[$field_name] = new Field($field_name);
    }

    /**
     * Remove a bunch of fields to the form if they exist
     *
     * @param array $field_names
     * @return void
     */
    public function removeFields(array $field_names)
    {
        foreach($field_names as $field_name)
        {
            if(isset($this->Fields[$field_name]))
            {
                unset($this->Fields[$field_name]);
            }
        }
    }

    /**
     * Remove a single field from the form if it exists
     *
     * @param string $field_name
     * @return void
     */
    public function removeField(string $field_name)
    {
        if(isset($this->Fields[$field_name]))
        {
            unset($this->Fields[$field_name]);
        }
    }

    /**
     * Remove a custom submit button by label if it exists
     *
     * @param string $label
     * @return void
     */
    public function removeSubmitButton(string $label)
    {
        foreach($this->submit_buttons as $key => $submit_button)
        {
            if($submit_button['label'] === $label)
            {
                unset($this->submit_buttons[$key]);
            }
        }
    }

    /**
     * Is $field_name a field
     *
     * @param string $field_name
     * @return bool
     */
    public function isField(string $field_name)
    {
        return isset($this->Fields[$field_name]) && is_object($this->Fields[$field_name]);
    }

    /**
     * Add a single multiple display fields field
     *
     * @param array $field_names
     * @return void
     */
    public function addDisplayFields(array $field_names)
    {
        foreach($field_names as $field)
        {
            $this->display_fields[$field] = $field;
        }
    }

    /**
     * Set the array of fields to be displayed (order matters)
     *
     * @param array $field_names
     * @return void
     * @throws \Exception
     */
    public function setDisplayFields(array $field_names)
    {
        // TODO: add validation on field_names?
        foreach($field_names as $field)
        {
            if(!isset($this->Fields[$field]))
            {
                throw new \Exception('"'.$field.'" is not a valid field name');
            }
        }

        $this->display_fields = $field_names;
    }

    /**
     * Add $display_field to the display array after $after_field
     *
     * @param string $display_field
     * @param string $after_field
     * @return void
     * @throws \Exception
     */
    public function setDisplayAfter(string $display_field, string $after_field)
    {
        foreach($this->display_fields as $key => $value)
        {
            if($value == $after_field)
            {
                $this->display_fields = array_merge(array_slice($this->display_fields, 0, $key+1), array($display_field), array_slice($this->display_fields, $key+1));
                return;
            }
        }

        throw new \Exception('Could not find "'.$after_field.'"');
    }

    /**
     * Remove a single field from the form
     *
     * @param array $field_name
     * @return void
     */
    public function getDisplayFields()
    {
        if(is_array($this->display_fields) && sizeof($this->display_fields) > 0)
        {
            $Fields = [];
            foreach($this->display_fields as $field_name)
            {
                $Fields[$field_name] = $this->{$field_name};
            }

            return $Fields;
        }

        return $this->Fields;
    }

    /**
     * Add field labels to the existing labels
     *
     * @param array $labels
     * @return void
     * @throws \Exception
     */
    public function setLabels(array $labels)
    {
        foreach($labels as $field_name => $label)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->label = $label;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

    /**
     * Get a list of all labels for the given $field_names, if $field_names is blank, get labels for all fields
     *
     * @param array $field_names
     * @return array
     * @throws \Exception
     */
    public function getLabels(array $field_names = [])
    {
        if(!is_array($field_names))
        {
            $field_names = $this->getFields();
        }

        $labels = [];
        foreach($field_names as $field_name)
        {
            if(isset($this->Fields[$field_name]))
            {
                $labels[$field_name] = $this->Fields[$field_name]->label;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }

        return $labels;
    }

    /**
     * Set multiple field values at once [field_name] => value
     *
     * @param array $values
     * @return void
     * @throws \Exception
     */
    public function setValues(array $values)
    {
        foreach($values as $field_name => $value)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->value = $value;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

    /**
     * Set multiple field types at once [field_name] => value
     *
     * @param array $types
     * @return void
     * @throws \Exception
     */
    public function setTypes($types)
    {
        foreach($types as $field_name => $type)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->type = $type;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

    /**
     * Set multiple field examples at once [field_name] => value
     *
     * @param array $examples
     * @return void
     * @throws \Exception
     */
    public function setExamples($examples)
    {
        foreach($examples as $field_name => $example)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->example = $example;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

    /**
     * Set multiple field default values at once [field_name] => value
     *
     * @param array $default_values
     * @return void
     * @throws \Exception
     */
    public function setDefaultValues(array $default_values)
    {
        foreach($default_values as $field_name => $default_value)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->default_value = $default_value;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

    /**
     * Set multiple field required fields at oncel takes array of field names
     *
     * @param array $required_fields
     * @return void
     * @throws \Exception
     */
    public function setRequiredFields(array $required_fields)
    {
        foreach($required_fields as $field_name)
        {
            if(isset($this->Fields[$field_name]))
            {
                $this->Fields[$field_name]->attributes->required = true;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" does not exist');
            }
        }
    }

}
