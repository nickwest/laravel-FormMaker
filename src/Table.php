<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

class Table{
    /**
     * Array of field names
     *
     * @var array
     */
    protected $display_fields = [];

    /**
     * Array of field names
     *
     * @var array
     */
    protected $field_replacements = [];

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
     * Collection that the table will display
     *
     * @var Illuminate\Support\Collection
     */
    protected $Collection = [];


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
        if($property == 'view_namespace') {
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
        foreach($field_names as $field_name) {
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
        if(!in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }
    }

    /**
     * Add a html replacement string for a field
     *
     * @param string $field field name
     * @param string $html non-escaped text to replace field value in output
     * @return void
     */
    public function addFieldReplacement(string $field, string $html)
    {
        $this->field_replacements[$field] = $html;
    }

    /**
     * Check if a field has a replacement pattern
     *
     * @param string $field field name
     * @return bool
     */
    public function hasFieldReplacement(string $field)
    {
        return isset($this->field_replacements[$field]);
    }

    /**
     * Get a field's replacement value
     *
     * @param string $field field name
     * @return string
     */
    public function getFieldReplacement(string $field, &$Object)
    {
        $pattern = '/\{([a-zA-Z0-9_]+)\}/';
        $results = [];
        preg_match_all($pattern, $this->field_replacements[$field], $results, PREG_PATTERN_ORDER);

        $replaced = $this->field_replacements[$field];

        if(is_array($results[0]) && is_array($results[1])) {
            foreach($results[0] as $key => $match) {
                if(is_object($Object) && isset($Object->{$results[1][$key]})) {
                    $replaced = str_replace($results[0][$key], (string)$Object->{$results[1][$key]}, $this->field_replacements[$field]);
                } elseif(is_array($Object) && isset($Object[$results[1][$key]])) {
                    $replaced = str_replace($results[0][$key], $Object[$results[1][$key]], $this->field_replacements[$field]);
                }
            }
        }

        return $replaced;
    }

    /**
     * Add css classes to the table
     *
     * @param array $classes Array of CSS classes
     * @return void
     */
    public function addClasses(array $classes)
    {
        foreach($classes as $class) {
            if(!in_array($class, $this->classes)) {
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
        if(in_array($class, $this->classes)) {
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
     * @param string $field_name
     * @param string $pattern
     * @return void
     */
    public function setLinkingPattern(string $field_name, string $pattern = '')
    {
        if($pattern == '') {
            if(isset($this->linking_patterns[$field_name])) {
                unset($this->linking_patterns[$field_name]);
            }
            return;
        }

        $this->linking_patterns[$field_name] = $pattern;
    }


    /**
     * Set a linking pattern by route name
     *
     * @param string $field_name
     * @param string $route_name
     * @param array $parameters keys to replace by value
     * @return void
     */
    public function setLinkingPatternByRoute(string $field_name, string $route_name, array $parameters=[])
    {
        $Route = Route::getRoutes()->getByName($route_name);
        if($Route == null) {
            throw new \Exception('Invalid route name '.$route_name);
        }

        $pattern = '/'.$Route->uri;

        foreach($parameters as $key => $value) {
            if(strpos($pattern, '{'.$key.'}') !== false) {
                $pattern = str_replace('{'.$key.'}', $value, $pattern);
            }
        }

        $this->linking_patterns[$field_name] = $pattern;
    }

    /**
     * Check if the field has a linking pattern
     *
     * @param string $field_name
     * @return void
     */
    public function hasLinkingPattern(string $field_name)
    {
        return isset($this->linking_patterns[$field_name]);
    }

    /**
     * Get a link from a linking pattern
     *
     * @param string $field_name
     * @param mixed $Object
     * @return void
     */
    public function getLink(string $field_name, &$Object)
    {
        $link = false;

        if(isset($this->linking_patterns[$field_name])) {
            $link = $this->linking_patterns[$field_name];
            $replacement = [];

            $pattern = '/\{([a-zA-Z0-9_]+)\}/';
            $results = [];
            preg_match_all($pattern, $this->linking_patterns[$field_name], $results, PREG_PATTERN_ORDER);

            if(is_array($results[0]) && is_array($results[1])) {
                foreach($results[0] as $key => $match) {
                    if(is_object($Object) && isset($Object->{$results[1][$key]})) {
                        $link = str_replace($results[0][$key], (string)$Object->{$results[1][$key]}, $link);
                    } elseif(is_array($Object) && isset($Object[$results[1][$key]])) {
                        $link = str_replace($results[0][$key], $Object[$results[1][$key]], $link);
                    } else {
                        return false;
                    }
                }
            }
        }

        return $link;
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
        foreach($labels as $field_name => $label) {
            if(isset($this->display_fields[$field_name])) {
                $this->labels[$field_name] = $label;
            } else {
                throw new \Exception('"'.$field_name.'" not set as a display field');
            }
        }
    }

    public function getLabel($field_name)
    {
        if(isset($this->labels[$field_name])) {
            return $this->labels[$field_name];
        }

        // This should always be done the same was as Field::makeLabel()
        return ucfirst(str_replace('_', ' ', $field_name));
    }

    /**
     * Set the Collection data to the Table Object
     *
     * @param Illuminate\Support\Collection $Collection
     * @return void
     */
    public function setData(\Illuminate\Support\Collection $Collection)
    {
        $this->Collection = $Collection;
    }

    /**
     * Make a view and extend $extends in $section, $blade_data is the data array to pass to View::make()
     *
     * @param array $blade_data
     * @param string $extends
     * @param string $section
     * @return View
     */
    public function makeView(array $blade_data = [], string $extends = '', string $section = '')
    {
        $blade_data['Table'] = $this;
        $blade_data['extends'] = $extends;
        $blade_data['section'] = $section;

        $this->Theme->prepareTableView($this);

        if($extends != '') {

            if($this->Theme->view_namespace != '' && View::exists($this->Theme->view_namespace.'::table-extend')) {
                return View::make($this->Theme->view_namespace.'::table-extend', $blade_data);
            }

            return View::make('form-maker::table-extend', $blade_data);
        }

        if($this->Theme->view_namespace != '' && View::exists($this->Theme->view_namespace.'::table')) {
            return View::make($this->Theme->view_namespace.'::table', $blade_data);
        }
        return View::make('form-maker::table', $blade_data);
    }
}
