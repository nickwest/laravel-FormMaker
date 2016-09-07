<?php namespace Nickwest\FormMaker;

use Illuminate\Support\Facades\View;

class Form{

	/**
	 * Array of Field Objects
	 *
	 * @var array
	 */
	protected $Fields = array();

	/**
	 * Array of field_names to display
	 *
	 * @var array
	 */
	protected $display_fields;

	/**
	 * Array of valid columns for hte model using this trait
	 *
	 * @var array
	 */
	protected $valid_columns = array();


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
	protected $url = '';

	/**
	 * The Days of the week that we use for storing daysofweek fields
	 *
	 * @var array
	 */
	protected $daysofweek = array('M' => 'Mon', 'T' => 'Tue', 'W' => 'Wed', 'R' => 'Thu', 'F' => 'Fri', 'S' => 'Sat', 'U' => 'Sun');

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct(){

	}

	/**
	 * Field  accessor
	 *
	 * @param string $field_name
	 * @return Field
	 */
	public function __get($field_name){
		if(isset($this->Fields[$field_name])){
			return $this->Fields[$field_name];
		}

		return null;
	}

	/**
	 * Field mutator
	 *
	 * @param string $key, string $value
	 * @return void
	 */
	public function __set($key, $value){
		return $this->setProperty($key, $value);
	}

	/**
	 * Field mutator
	 *
	 * @param string $key, string $value
	 * @return void
	 */
	public function setProperty($key, $value){
		if(isset($this->{$key})){
			$this->{$key} = $value;
		}else{
			throw new \Exception('Invalid property');
		}
	}

	public function getDaysOfWeekValues(){
		return $this->daysofweek;
	}

	/**
	 * Get the whole Fields array
	 *
	 * @return array
	 */
	public function getFields(){
		return $this->Fields;
	}

	/**
	 * Get the allow_delete value
	 *
	 * @return bool
	 */
	public function getAllowDelete($value=''){
		return $this->allow_delete;
	}

	/**
	 * Make a view and extend $extends in section $section, $blade_data is the data array to pass to View::make()
	 *
	 * @param array $blade_data, string $extends, string $section
	 * @return View
	 */
	public function makeView($blade_data, $extends='', $section=''){
		$this->validateFormStructure();

		foreach($this->display_fields as $field){
			$this->Fields[$field]->setupAttributes();
		}

		$blade_data['Form'] = $this;
		$blade_data['extends'] = $extends;
		$blade_data['section'] = $section;
		if($extends != '')
			return View::make('form-maker::form-extend', $blade_data);

			return View::make('form-maker::form', $blade_data);
	}

	/**
	 * Step through fields and make sure they're legit
	 *
	 * @return void
	 */
	public function validateFormStructure()
	{
		foreach($this->Fields as $Field){
			$Field->validateFieldStructure();
		}

		// TODO: Add any additional Form validation here
	}

	/**
	 * add a bunch of fields to the form
	 *
	 * @param array $field_names
	 * @return void
	 */
	public function addFields($field_names){
		foreach($field_names as $field_name){
			$this->Fields[$field_name] = new Field($field_name);
		}
	}

	/**
	 * 
	 * add a Subform into the current form
	 *
	 * @param string $name, Form $form, string $before_field
	 * @return void
	 */
	public function addSubform($name, $Form, $before_field=null){
		$this->addField($name);
		$this->Fields[$name]->type = 'subform';
		$this->Fields[$name]->subform = $Form;

		if($before_field != null){
			foreach($this->display_fields as $key => $value){
				if($value == $before_field) {
					$this->display_fields = array_merge(array_slice($this->display_fields, 0, $key), array($name), array_slice($this->display_fields, $key));
					return true;
				}
			}
		}
		$this->display_fields[] = $name;
	}

	public function formatValue($field_name, $value){
		if(isset($this->Fields[$field_name])){
			return $this->Fields[$field_name]->formatValue($value);
		}

		return false;
	}

	/**
	 * Add a single field to the form
	 *
	 * @param array $field_name
	 * @return void
	 */
	public function addField($field_name){
		$this->Fields[$field_name] = new Field($field_name);
	}

	/**
	 * Remove a bunch of fields to the form
	 *
	 * @param array $field_names
	 * @return void
	 */
	public function removeFields($field_names){
		foreach($field_names as $field_name){
			if(isset($this->Fields[$field_name])){
				unset($this->Fields[$field_name]);
			}
		}
	}

	/**
	 * Remove a single field from the form
	 *
	 * @param array $field_name
	 * @return void
	 */
	public function removeField($field_name){
		if(isset($this->Fields[$field_name])){
			unset($this->Fields[$field_name]);
		}
	}

	/**
	 * Is $field_name a field
	 *
	 * @param string $field_name
	 * @return bool
	 */
	public function isField($field_name){
		return isset($this->Fields[$field_name]) && is_object($this->Fields[$field_name]);
	}

	/**
	 * Set the array of fields to be displayed (order matters)
	 *
	 * @param array $field_names
	 * @return void
	 */
	public function setDisplayFields($field_names){
		// TODO: add validation on field_names?
		if(is_array($field_names)){
			$this->display_fields = $field_names;
		}
	}

	/**
	 * add $display_field to the display array after $after_field
	 *
	 * @param string $display_field, string $after_field
	 * @return bool
	 */
	public function setDisplayAfter($display_field, $after_field){
		foreach($this->display_fields as $key => $value){
			if($value == $after_field){
				$this->display_fields = array_merge(array_slice($this->display_fields, 0, $key+1), array($display_field), array_slice($this->display_fields, $key+1));
				return true;
			}
		}
		return false;
	}

	/**
	 * Remove a single field from the form
	 *
	 * @param array $field_name
	 * @return void
	 */
	public function getDisplayFields(){
		if(is_array($this->display_fields) && sizeof($this->display_fields) > 0){
			$Fields = array();
			foreach($this->display_fields as $field_name){
				$Fields[$field_name] = $this->{$field_name};
			}

			return $Fields;
		}

		return $this->Fields;
	}

	/**
	 * Set multiple field labels at once [field_name] => value
	 *
	 * @param array $labels
	 * @return void
	 */
	public function setLabels($labels){
		if(!is_array($labels)) return;

		foreach($labels as $field_name => $label){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->label = $label;
			}
		}
	}

	/**
	 * Get a list of all labels for the given $field_names, if $field_names is blank, get labels for all fields
	 *
	 * @param array $field_names
	 * @return array
	 */
	public function getLabels($field_names=array()){
		if(!is_array($field_names)){
			$field_names = $this->getFields();
		}

		$labels = array();
		foreach($field_names as $field_name){
			if(isset($this->Fields[$field_name])){
				$labels[$field_name] = $this->Fields[$field_name]->label;
			}
		}

		return $labels;
	}

	/**
	 * Set multiple field values at once [field_name] => value
	 *
	 * @param array $values
	 * @return void
	 */
	public function setValues($values){
		if(!is_array($values)) return;

		foreach($values as $field_name => $value){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->value = $value;
			}
		}
	}

	/**
	 * Set multiple field types at once [field_name] => value
	 *
	 * @param array $types
	 * @return void
	 */
	public function setTypes($types){
		if(!is_array($types)) return;

		foreach($types as $field_name => $type){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->type = $type;
			}
		}
	}

	/**
	 * Set multiple field examples at once [field_name] => value
	 *
	 * @param array $examples
	 * @return void
	 */
	public function setExamples($examples){
		if(!is_array($examples)) return;

		foreach($examples as $field_name => $example){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->example = $example;
			}
		}
	}

	/**
	 * Set multiple field default values at once [field_name] => value
	 *
	 * @param array $default_values
	 * @return void
	 */
	public function setDefaultValues($default_values){
		if(!is_array($default_values)) return;

		foreach($default_values as $field_name => $default_value){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->default_value = $default_value;
			}
		}
	}

	/**
	 * Set multiple field required fields at oncel takes array of field names
	 *
	 * @param array $required_fields
	 * @return void
	 */
	public function setRequiredFields($required_fields){
		if(!is_array($required_fields)) return;

		foreach($required_fields as $field_name){
			if(isset($this->Fields[$field_name])){
				$this->Fields[$field_name]->is_required = true;
			}
		}
	}

}
