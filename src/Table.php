<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Facades\Excel;

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
     * Excel Config values from client
     *
     * @var array
     */
    protected $excel_config = [];


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
    public function setLinkingPatternByRoute(string $field_name, string $route_name, array $parameters=[], $query_string=[])
    {
        $Route = Route::getRoutes()->getByName($route_name);
        if($Route == null) {
            throw new \Exception('Invalid route name '.$route_name);
        }

        $replaced = '/'.$Route->uri;

        $pattern = '/\{(.*?)\??\}/';
        $results = [];
        preg_match_all($pattern, $replaced, $results, PREG_PATTERN_ORDER);

        if(is_array($results[0]) && is_array($results[1])) {
            foreach($results[0] as $key => $match) {
                if(isset($parameters[$results[1][$key]])) {
                    $replaced = str_replace($results[0][$key], $parameters[$results[1][$key]], $replaced);
                }
            }
        }

        if(count($query_string) > 0){
            $pieces = [];
            foreach($query_string as $key => $value){
                $pieces[] = $key.'='.$value;
            }
            $replaced = $replaced.'?'.implode('&', $pieces);
        }

        $this->linking_patterns[$field_name] = $replaced;
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

    /**
     * Export this to excel
     *
     * @param string $title
     * @param array $config
     * @return void
     */
    public function exportToExcel(string $title, array $config)
    {
        $this->excel_config = $config;

        foreach($this->display_fields as $field){
            $headings[] = $this->getLabel($field);
        }

        $export = array_merge([$headings], $this->Collection->map(function($item){
            $collection = new Collection($item);
            return $collection->only($this->display_fields)->all();
        })->toArray());

        Excel::create($title, function($Excel) use ($title, $config, $export) {
            $Excel->setTitle($title)
                    ->setCreator($this->config('Creator', ''))
                    ->setCompany($this->config('Company', ''))
                    ->setDescription($this->config('Description', ''));

            $Excel->sheet($title, function($Sheet) use ($config, $export){
                // Set font
                $Sheet->setFontFamily($this->config('FontFamily', 'Calibri'));
                $Sheet->setFontSize($this->config('FontSize', 16));
                $Sheet->setFontBold($this->config('FontBold', false));

                // Set up the page
                $Sheet->sethorizontalCentered($this->config('horizontalCentered', false));
                $Sheet->setfitToPage($this->config('fitToPage', false));
                $Sheet->setfitToHeight($this->config('fitToHeight', false));
                $Sheet->setfitToWidth($this->config('fitToWidth', true));
                $Sheet->setpaperSize($this->config('paperSize', 1));

                // Set margins
                $Sheet->setPageMargin($this->config('PageMargin', array(0.7, 0.25, 0.25, 0.25)));

                // Populate data
                $Sheet->fromArray($export, null, 'A1', false, false);

                $highest_col = $Sheet->getHighestColumn();

                $total_rows = count($export);

                // Format all rows as text
                $Sheet->setColumnFormat(array(
                    'A1:'.$highest_col.$total_rows => '@',
                ));

                // Add borders
                if($this->config('borders', false)){
                    $Sheet->setBorder('A1:'.$highest_col.$total_rows, 'thin');
                }

                // Make the first row bold
                $Sheet->cell('A1:'.$highest_col.'1', function($cells){
                    $cells->setFontWeight('bold');
                });

                // Zebra rows
                if($this->config('zebraRows', true)) {
                    for($i = 2; $i <= $total_rows; $i++){
                        if($i % 2 == 0){
                            $Sheet->cell('A'.$i.':'.$highest_col.$i, function($cells){
                                $cells->setBackground('#EEEEEE');
                            });
                        }
                    }
                }

                // Verticle align
                $Sheet->cell('A1:'.$highest_col.$total_rows, function($cells){
                    $cells->setValignment('top');
                });

            });

        })->export('xls');
    }

    protected function config(string $key, $default)
    {
        return (isset($this->excel_config[$key]) ? $this->excel_config[$key] : $default);
    }
}
