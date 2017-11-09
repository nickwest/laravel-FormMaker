<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

trait FormTrait{
    /**
     * Form object see Nickwest\FormMaker\Form
     *
     * @var Form
     */
    protected $Form = null;

    protected $valid_columns = array();
    protected $columns = array();

    protected $blank_select_text = '-- Select One --';
    protected $label_postfix = '';

    protected $multi_delimiter = '|';

    protected $validation_rules = [];


    /**
     * Boot the trait. Adds an observer class for form
     *
     * @return void
     */
    public static function bootFormTrait()
    {
        // function save() method is hooked by FormObserver and runs validation
        static::observe(new FormObserver);
    }

    /**
     * Boot the trait. Adds an observer class for form
     *
     * @return Form
     */
    public function Form()
    {
        if(!is_object($this->Form))
        {
            $this->Form = new Form();
        }

        return $this->Form;
    }

    /**
     * Make a View for the form and return the rendered output
     *
     * @param array $blade_data
     * @param string $extends
     * @param string $section
     * @return View
     */
    public function getFormView(array $blade_data, string $extends = '', string $section = '')
    {
        return $this->Form()->makeView($blade_data, $extends, $section);
    }

    /**
     * Make a View for the field and return the rendered output
     *
     * @param string $field_name
     * @param array $options
     * @return View
     */
    public function getFieldView(string $field_name, $options=array())
    {
        return $this->Form()->$field_name->makeView($options);
    }

    /**
     * Make a View for the field and return the rendered output
     *
     * @param string $field_name
     * @param array $options
     * @return View
     */
    public function getFieldDisplayView($field_name, $options=array())
    {
        return $this->Form()->$field_name->makeDisplayView($options);
    }


    /**
     * Set the values from a post data array to $this model,
     * returned bool indicates if anything changed
     *
     * @param array $post_data
     * @return void
     */
    public function setPostValues($post_data){
        foreach($post_data as $field_name => $value)
        {
            if($this->isColumn($field_name) && $this->isFillable($field_name))
            {
                $this->Form()->{$field_name} = $value;
                if($this->Form()->{$field_name}->multiple || $this->Form()->{$field_name}->type == 'checkbox')
                {
                    $this->{$field_name} = implode($this->multi_delimiter, $value);
                }
                else
                {
                    $this->{$field_name} = $value;
                }
            }
        }

        // Make sure no Form fields were omitted from the post array (checkboxes can be when none are set)
        foreach($this->Form()->getDisplayFields() as $Field)
        {
            if(isset($post_data[$Field->name]) || !$this->isFillable($Field->original_name))
            {
                continue;
            }

            // If they were omitted set it to null
            if($this->Form()->{$Field->original_name} != '')
            {
                $this->Form()->{$Field->original_name} = null;
                $this->{$Field->original_name} = null;
            }
        }
    }

    /**
     * Validation of required fields and stuff
     *
     * @var bool
     */
    public function isValid()
    {
        // Add required fields to field_rules
        $field_rules = array();
        foreach($this->Form()->getFields() as $Field)
        {
            if($Field->attributes->required)
            {
                $field_rules[$Field->original_name] = array('required');
            }
        }

        // Set up the Validator
        $Validator = Validator::make(
            $this->getAttributes(),
            $field_rules
        );

        // Set error messages to fields
        if(!($success = !$Validator->fails()))
        {
            foreach($Validator->errors()->toArray() as $field => $error)
            {
                $this->Form()->$field->error_message = current($error);
            }
        }

        return $success;
    }

    /**
     * Set all of the form values to whatever the value on that attribute of the model is
     *
     * @return void
     */
    public function setAllFormValues()
    {
        foreach($this->Form()->getFields() as $Field)
        {
            if($Field->type == 'daysofweek')
            {
                $data = (isset($this->{$Field->original_name}) ? explode($this->multi_delimiter, $this->{$Field->original_name}) : ($this->Form()->{$Field->original_name}->default_value != '' ? $this->Form()->{$Field->original_name}->default_value : array()));
                foreach($this->Form()->getDaysOfWeekValues() as $key => $day)
                {
                    if(in_array($key, $data))
                    {
                        $return[$key] = 1;
                    }
                    else
                    {
                        $return[$key] = 0;
                    }
                }
                $this->Form()->{$Field->original_name}->value = $return;
            }
            elseif($Field->type == 'checkbox' || $Field->multiple)
            {
                if((!isset($this->{$Field->original_name}) || ($this->{$Field->original_name} == '' && $this->{$Field->original_name} !== 0)) && $this->Form()->{$Field->original_name}->default_value != '')
                {
                    $this->Form()->{$Field->original_name}->value = $this->Form()->{$Field->original_name}->default_value;
                }
                else
                {
                    $values = array();
                    foreach(explode($this->multi_delimiter, $this->{$Field->original_name}) as $value)
                    {
                        $values[$value] = $value;
                    }

                    $this->Form()->{$Field->original_name} = $values;
                }
            }
            else
            {
                $this->Form()->{$Field->original_name} =
                (
                    isset($this->{$Field->original_name})
                    ? $this->{$Field->original_name}
                    : (
                        $this->Form()->{$Field->original_name}->default_value != ''
                        ? $this->Form()->{$Field->original_name}->default_value
                        : ''
                    )
                );
            }
        }
    }

    /**
     * Determine if $field_name is a Column in the table this model models
     *
     * @param string $field_name
     * @return bool
     */
    public function isColumn($field_name)
    {
        if(sizeof($this->valid_columns) <= 0)
        {
            $this->getAllColumns();
        }

        if(isset($this->valid_columns[$field_name]))
        {
            return true;
        }

        return false;
    }

    /**
     * Get a list of form data to build a form
     *
     * @return void
     */
    protected function generateFormData()
    {
        $columns = $this->getAllColumns();

        foreach($columns as $column)
        {
            $this->Form()->addField($column['name']);
            $this->Form()->{$column['name']}->setOptions($column['values']);
            $this->Form()->{$column['name']}->attributes->maxlength = $column['length'];
            $this->Form()->{$column['name']}->default_value = $column['default'];
            $this->Form()->{$column['name']}->attributes->type = $this->getFormTypeFromColumnType($column['type']);
            $this->Form()->addDisplayFields([$column['name']]);
        }
    }

    /**
     * Get a list of all valid columns on the model using this trait
     *
     * @return array
     */
    protected function getAllColumns()
    {
        if(count($this->columns) > 0)
        {
            return $this->columns;
        }

        $query = 'SHOW COLUMNS FROM '.$this->table;

        foreach(DB::connection($this->connection)->select($query) as $column)
        {
            $this->addColumn(
                $column->Field,
                $this->getType($column->Type),
                $column->Default,
                $this->getLength($column->Type),
                $this->getEnumOptions($column->Type, $column->Null == 'YES')
            );
            $this->valid_columns[$column->Field] = $column->Field;
        }

        return $this->columns;
    }

    /**
     * Get a list of all valid columns on the model using this trait
     *
     * @param string $name
     * @param string $type
     * @param string $default
     * @param int $length
     * @param mixed $values
     * @return void
     */
    protected function addColumn($name, $type, $default, $length, $values)
    {
        $this->columns[$name] = array(
            'name' => $name,
            'type' => $type,
            'default' => $default,
            'length' => $length,
            'values' => $values,
        );
    }

    /**
     * Isolate and return the column type
     *
     * @param string $type
     * @return string
     */
    private function getType($type)
    {
        $types = array(
            'int', 'tinyint', 'smallint', 'mediumint', 'bigint',
            'decimal', 'float', 'double', 'real',
            'bit', 'boolean', 'serial',
            'date', 'datetime', 'timestamp', 'time', 'year',
            'char', 'varchar',
            'tinytext', 'text', 'mediumtext', 'longtext',
            'binary', 'varbinary',
            'tinyblob', 'mediumblob', 'blob', 'longblob',
            'enum', 'set',
        );


        foreach($types as $key)
        {
            if(strpos($type, $key) === 0)
            {
                return $key;
            }
        }
    }

    /**
     * Isolate and return the column length
     *
     * @param string $type
     * @return int
     */
    private function getLength($type)
    {
        if(strpos($type, 'enum') === 0)
        {
            return;
        }

        if(strpos($type, '(') !== false)
        {
            return substr($type, strpos($type, '(')+1, strpos($type, ')') - strpos($type, '(')-1);
        }

        $lengths = array(
            'tinytext' => 255,
            'text' => 65535,
            'mediumtext' => 1677215,
            'longtext' => 4294967295,

        );

        foreach($lengths as $key => $length)
        {
            if(strpos($type, $key) === 0)
            {
                return $length;
            }
        }

    }

    /**
     * Isolate and return the values for enums
     *
     * @param string $type
     * @param bool $nullable
     * @return array
     */
    private function getEnumOptions($type, $nullable=false)
    {
        if(strpos($type, 'enum') !== 0)
            return;
        $values = explode(',', str_replace("'", '', substr($type, strpos($type, '(')+1, strpos($type, ')') - strpos($type, '(')-1)));

        foreach($values as $value)
        {
            if($value == '')
            {
                $return_array[$value] = $this->blank_select_text;
            }
            else
            {
                $return_array[$value] = $value;
            }
        }

        if(!isset($return_array['']) && $nullable)
        {
            $return_array = array_merge(['' => $this->blank_select_text], $return_array);
        }

        return $return_array;
    }

    /**
     * Get the field type based on column type
     *
     * @param string $type
     * @return string
     */
    private function getFormTypeFromColumnType($type)
    {
        switch($type)
        {
            // TODO: Expand on this with more HTML5 field types
            case 'enum':
                return 'select';

            case 'text':
            case 'tinytext':
            case 'mediumtext':
            case 'longtext':
                return 'textarea';

            default:
                return 'text';
        }
    }


}
