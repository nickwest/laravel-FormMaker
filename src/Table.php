<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;

class Table{
    /**
     * Array of field names
     *
     * @var array
     */
    protected $display_fields = [];

    /**
     * Theme to use
     *
     * @var string
     */
    protected $Theme = 'core';

    /**
     * Array of labels, keyed by field_name
     *
     * @var array
     */
    protected $labels = [];

    /**
     * Array of data keyed by field_name
     *
     * @var array
     */
    protected $data = [];

    /**
     * Array of css classes
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Array of column linking patterns keyed by field_name
     *
     * @var array
     */
    protected $linking_patterns = [];


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->Theme = new DefaultTheme();
    }

    /**
     * Member accessor
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

        return $this->$property;
    }

    /**
     * member mutator
     *
     * @param array $field_names
     * @return void
     */
    public function setDisplayFields(array $field_names)
    {
        foreach($field_names as $field_name)
        {
            $this->display_fields[$field_name] = $field_name;
        }
    }

    /**
     * Add a css class to the table
     *
     * @param string $class css class
     * @return void
     */
    public function addClass(string $class)
    {
        if(!in_array($class, $this->classes))
        {
            $this->classes[] = $class;
        }
    }

    /**
     * Add css classes to the table
     *
     * @param array $classes Array of CSS classes
     * @return void
     */
    public function addClasses(array $classes)
    {
        foreach($classes as $class)
        {
            if(!in_array($class, $this->classes))
            {
                $this->classes[] = $class;
            }
        }
    }

    /**
     * Remove a css class to the table
     *
     * @param string $class css class
     * @return void
     */
    public function removeClass(string $class)
    {
        if(in_array($class, $this->classes))
        {
            $remove = [$class];
            $this->classes = array_diff($this->classes, $remove);
        }
    }

    /**
     * Get a string of CSS class names
     *
     * @return string
     */
    public function getClassesString()
    {
        return implode(' ', $this->classes);
    }

    /**
     * Set a linking pattern
     *
     * @return void
     */
    public function setLinkingPattern(string $field_name, string $pattern = '')
    {
        if($pattern == '')
        {
            if(isset($this->linking_patterns[$field_name]))
            {
                unset($this->linking_patterns[$field_name]);
            }
            return;
        }

        $this->linking_patterns[$field_name] = $pattern;
    }

    public function getLinkView(array $data_row, string $field_name)
    {

    }


    /**
     * Display Fields Accessor
     *
     * @return array
     */
    public function getDisplayFields()
    {
        return $this->display_fields;
    }

    /**
     * Set the theme
     *
     * @param \Nickwest\FormMaker\Theme $Theme
     * @return void
     */
    public function setTheme(\Nickwest\FormMaker\Theme $Theme)
    {
        $this->Theme = $Theme;
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
            if(isset($this->display_fields[$field_name]))
            {
                $this->labels[$field_name] = $label;
            }
            else
            {
                throw new \Exception('"'.$field_name.'" not set as a display field');
            }
        }
    }

    public function getLabel($field_name)
    {
        if(isset($this->labels[$field_name]))
        {
            return $this->labels[$field_name];
        }

        // This should always be done the same was as Field::makeLabel()
        return ucfirst(str_replace('_', ' ', $field_name));
    }

    /**
     * Set the data to be displayed
     *
     * @param array $labels
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Make a view and extend $extends in $section, $blade_data is the data array to pass to View::make()
     *
     * @param array $blade_data
     * @param string $extends
     * @param string $section
     * @return View
     */
    public function makeView(array $blade_data, string $extends = '', string $section = '')
    {
        $blade_data['Table'] = $this;
        $blade_data['extends'] = $extends;
        $blade_data['section'] = $section;

        $this->Theme->prepareTableView($this);

        if($extends != '')
        {
            if($this->Theme->view_namespace != '' && View::exists($this->Theme->view_namespace.'::table-extend'))
            {
                return View::make($this->Theme->view_namespace.'::table-extend', $blade_data);
            }
            return View::make('form-maker::table-extend', $blade_data);
        }
        if($this->Theme->view_namespace != '' && View::exists($this->Theme->view_namespace.'::table'))
        {
            return View::make($this->Theme->view_namespace.'::table', $blade_data);
        }
        return View::make('form-maker::table', $blade_data);
    }
}
