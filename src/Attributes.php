<?php namespace Nickwest\FormMaker;

class Attributes{
    /**
     * Valid field attributes (HTML5)
     *
     * @var string
     */
    protected $valid_attributes = [
        'global' => [
            'accesskey', 'class', 'contenteditable', 'contextmenu', 'data-*', 'dir',
            'draggable', 'dropzone', 'hidden', 'id', 'lang', 'spellcheck', 'style',
            'tabindex', 'title', 'translate',
        ],
        'textarea' => [
            'autofocus', 'cols', 'dirname', 'disabled', 'form', 'maxlength', 'name',
            'readonly', 'required', 'rows', 'wrap',
        ],
        'select' => [
            'autofocus', 'disabled', 'form', 'multiple', 'name', 'required',
        ],
        'input' => [
            'autofocus', 'disabled', 'list', 'maxlength', 'name', 'readonly',
            'type', 'value',
        ],
        'button' => [

        ],
        'checkbox' => [
            'checked', 'required',
        ],
        'color' => [
            'autocomplete', 'required',
        ],
        'date' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
        'datetime' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
        'datetime-local' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
        'email' => [
            'autocomplete', 'multiple', 'pattern', 'placeholder', 'required', 'size',
        ],
        'file' => [
            'accept', 'multiple', 'required',
        ],
        'hidden' => [

        ],
        'image' => [
            'align', 'alt', 'height', 'src', 'width',
        ],
        'month' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
        'number' => [
             'max', 'min', 'required', 'step',
        ],
        'password' => [
            'autocomplete', 'pattern', 'placeholder', 'required', 'size',
        ],
        'radio' => [
            'checked', 'required',
        ],
        'range' => [
            'autocomplete', 'max', 'min', 'step',
        ],
        'reset' => [

        ],
        'search' => [
            'autocomplete', 'pattern', 'placeholder', 'required', 'size',
        ],
        'submit' => [

        ],
        'tel' => [
            'autocomplete', 'pattern', 'placeholder', 'required', 'size',
        ],
        'text' => [
            'autocomplete', 'dirname', 'pattern', 'placeholder', 'required', 'size',
        ],
        'time' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
        'url' => [
            'autocomplete', 'pattern', 'placeholder', 'required', 'size',
        ],
        'week' => [
            'autocomplete', 'max', 'min', 'pattern', 'required', 'step',
        ],
    ];

    protected $flat_attributes = [
        'checked', 'disabled', 'multiple', 'readonly', 'required'
    ];

    /**
     * Keep track of classes separately so we can build it all pretty like
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Field Attributes (defaults are set in constructor)
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Field property and attribute accessor
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get(string $attribute)
    {
        if($attribute == 'class')
        {
            return implode(' ', $this->classes);
        }

        if(isset($this->attributes[$attribute]))
        {
            return $this->attributes[$attribute];
        }

        return null;
    }

    /**
     * Field property mutator
     *
     * @param string $property
     * @param mixed $value
     * @return void
     * @throws \Exception
     */
    public function __set(string $attribute, $value)
    {
        if($this->isValidAttribute($attribute))
        {
            if($attribute == 'class')
            {
                $this->classes = explode(' ', $value);
                return;
            }

            $this->attributes[$attribute] = $value;
            return;
        }

        throw new \Exception('"'.$attribute.'" is not a valid attribute');
    }

    /**
     * Output all attributes as a string
     *
     * @return string
     */
    public function __tostring()
    {
        return $this->getString();
    }


    /**
     * Add a css class
     *
     * @param string $class_name
     * @return void
     */
    public function addClass(string $class_name)
    {
        if(trim($class_name) != '')
        {
            $this->classes[$class_name] = $class_name;
        }
    }

    /**
     * Remove a css class
     *
     * @param string $class_name
     * @return void
     */
    public function removeClass(string $class_name)
    {
        if(isset($this->classes['class_name']))
        {
            unset($this->classes[$class_name]);
        }
    }

    /**
     * Output all attributes as a string
     *
     * @return string
     */
    public function getString(){
        $output = [];

        if(count($this->classes) > 0)
        {
            $this->attributes['class'] = '';
        }

        foreach($this->attributes as $key => $value)
        {
            if($key == 'class')
            {
                $value = implode(' ', $this->classes);
            }

            if($key == 'name' && ($this->attributes['type'] == 'checkbox' || (isset($this->attributes['multiple']) && $this->attributes['multiple'])))
            {
                $value .= '[]';
            }

            if(in_array($key, $this->flat_attributes))
            {
                if($value)
                {
                    $output[] = ' '.$key;
                }
            }
            else
            {
                $output[] = $key.'="'.$value.'"';
            }
        }

        return implode(' ', $output);
    }

    /**
     * Make a label for the given field, uses $this->label if available, otherwises generates based on field name
     *
     * @param string $key
     * @return bool
     */
    public function isValidAttribute($key)
    {
        foreach($this->valid_attributes as $type => $attributes)
        {
            // NOTE: Could make it so this is strict based on "type"
            foreach($attributes as $attribute)
            {
                if($attribute == $key)
                {
                    return true;
                }
            }
        }
        return false;
    }
}
