<?php namespace Nickwest\FormMaker;

use DB;

trait FormTrait{
	/**
	 * Form object see Nickwest\FormMaker\Form
	 *
	 * @var Form
	 */

	protected $Form = null;
	
	protected $valid_columns = array();
	
	public $blank_select_text = '-- Select One --';
		
	/**
	 * Boot the trait. Adds an observer class for form
	 *
	 * @return void
	 */
	public static function bootFormTrait()
	{
		static::observe(new FormObserver);
	}

	/**
	 * Boot the trait. Adds an observer class for form
	 *
	 * @var bool
	 */
	public function Form(){
		if(!is_object($this->Form)){
			$this->Form = new Form();
		}
		
		return $this->Form;
	}
	
	public function setAllFormValues(){
		foreach($this->attributes as $key => $value){
			if(is_object($this->Form()->{$key})){
				$this->Form()->{$key}->value = $value;
			}else{
				\Helpers::Pre($key);
			}
		}
	}
	
	public function isColumn($field_name){
		if(sizeof($this->valid_columns) <= 0){
			$this->getAllColumns();
		}
		
		if(isset($this->valid_columns[$field_name])){
			return true;
		}
		
		return false;
	}
	
	
	// Get a list of form data to build a form
	protected function generateFormData(){
		$columns = $this->getAllColumns();
				
		foreach($columns as $column){
			$this->Form()->addField($column['name']);
			$this->Form()->{$column['name']}->options = $column['values'];
			$this->Form()->{$column['name']}->max_length = $column['length'];
			$this->Form()->{$column['name']}->default_value = $column['default'];
			$this->Form()->{$column['name']}->type = $this->getFormTypeFromColumnType($column['type']);
			
/*
			$this->form_data['fields'][$column['name']] = array(
				'key' => $column['name'],
				'name' => $this->getPreset('name', $column['name']),
				'required' => ($this->getPreset('required', $column['name']) != '' ? $this->getPreset('required', $column['name']) : in_array($column['name'], $this->form_required_fields)),
				'type' => ($this->getPreset('type', $column['name']) != '' ? $this->getPreset('type', $column['name']) : $this->getFormType($column['type'])),
				'default' => ($this->getPreset('default', $column['name']) != '' ? $this->getPreset('default', $column['name']) : $column['default']),
				'max_length' => ($this->getPreset('length', $column['name']) != '' ? $this->getPreset('length', $column['name']) : $column['length']),
				'value' => $this->{$column['name']},
				'values' => ($this->getPreset('values', $column['name']) != '' ? $this->getPreset('values', $column['name']) : $column['values']),
			);

			if($this->form_data['fields'][$column['name']]['type'] == 'days_of_week'){
				$days = explode('|', $this->form_data['fields'][$column['name']]['value']);
				$this->form_data['fields'][$column['name']]['value'] = array();
				
				foreach($this->day_abbreviations as $day){
					if(in_array($day, $days)){
						$this->form_data['fields'][$column['name']]['value'][$day] = true;
					}else{
						$this->form_data['fields'][$column['name']]['value'][$day] = false;
					}
				}
			}
*/
		}
	}
	
	/**
	 * Get a list of all valid columns on the model using this trait
	 *
	 * @return array
	 */
	protected function getAllColumns(){
        $query = 'SHOW COLUMNS FROM '.$this->table;
        
        $columns = array();
        foreach(DB::select($query) as $column){
            $columns[$column->Field] = array(
            	'name' => $column->Field,
            	'type' => $this->getType($column->Type),
            	'default' => $column->Default,
            	'length' => $this->getLength($column->Type),
            	'values' => $this->getEnumOptions($column->Type),
            );
            $this->valid_columns[$column->Field] = $column->Field;
        }
        
        return $columns;
	}

	// isolate and return the column type
	private function getType($type){
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
		
		
		foreach($types as $key){
			if(strpos($type, $key) === 0){
				return $key;
			}
		}
	}

	// isolate and return the column length
	private function getLength($type){
		if(strpos($type, 'enum') === 0)
			return;
		
		if(strpos($type, '(') !== false){
			return substr($type, strpos($type, '(')+1, strpos($type, ')') - strpos($type, '(')-1);
		}

		$lengths = array(
			'tinytext' => 255,
			'text' => 65535,
			'mediumtext' => 1677215,
			'longtext' => 4294967295,
			
		);
				
		foreach($lengths as $key => $length){
			if(strpos($type, $key) === 0){
				return $length;
			}
		}

	}

	// isolate and return the values for enums
	private function getEnumOptions($type){
		if(strpos($type, 'enum') !== 0)
			return;
		$values = explode(',', str_replace("'", '', substr($type, strpos($type, '(')+1, strpos($type, ')') - strpos($type, '(')-1)));
		
		foreach($values as $value){
			if($value == ''){
				$return_array[$value] = $this->blank_select_text;
			}else{
				$return_array[$value] = $value;
			}
		}
		return $return_array;
	}
	
	// Get the form type based on column type
	private function getFormTypeFromColumnType($type){
		switch($type){
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