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
	public function Form()
	{
		if(!is_object($this->Form))
		{
			$this->Form = new Form();
		}
		
		return $this->Form;
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
				$data = (isset($this->{$Field->name}) ? explode('|', $this->{$Field->name}) : array());
				foreach($this->Form()->getDaysOfWeekValues() as $key => $day){
					if(in_array($key, $data))
					{
						$return[$key] = 1;	
					}
					else
					{
						$return[$key] = 0;
					}
				}
				$this->Form()->{$Field->name}->value = $return;
			}
			elseif($Field->type == 'checkbox')
			{
				$this->Form()->{$Field->name}->value = (!isset($this->{$Field->name}) || $this->{$Field->name} == ''  ? array() : explode('|', $this->{$Field->name}));
			}
			else
			{
				$this->Form()->{$Field->name}->value = (isset($this->{$Field->name}) ? $this->{$Field->name} : '');
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
			$this->Form()->{$column['name']}->options = $column['values'];
			$this->Form()->{$column['name']}->max_length = $column['length'];
			$this->Form()->{$column['name']}->default_value = $column['default'];
			$this->Form()->{$column['name']}->type = $this->getFormTypeFromColumnType($column['type']);
		}
	}
	
	/**
	 * Get a list of all valid columns on the model using this trait
	 *
	 * @return array
	 */
	protected function getAllColumns()
	{
        $query = 'SHOW COLUMNS FROM '.$this->table;
        
        $columns = array();
        foreach(DB::select($query) as $column)
        {
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
			return;
		
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
	 * @return array
	 */
	private function getEnumOptions($type)
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