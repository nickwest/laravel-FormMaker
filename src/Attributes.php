<?php namespace Nickwest\FormMaker;

class Attributes{
	/**
	 * Valid field attributes (HTML5)
	 *
	 * @var string
	 */
	protected $valid_attributes = [
		'input' => [
			'autofocus', 'class', 'disabled', 'height', 'id', 'list', 'max', 'maxlength',
			'min', 'multiple', 'name', 'pattern', 'placeholder', 'readonly',
			'required', 'size', 'step', 'type', 'value', 'width',
		],
		'text' => [
			'autocomplete', 'dirname',
		],
		'image' => [
			'alt', 'src',
		],
		'file' => [
			'accept',
		],
		'checkbox' => [
			'checked',
		],
		'radio' => [
			'checked',
		],
		'textarea' => [
			'autofocus', 'cols', 'dirname', 'disabled', 'maxlength', 'name',
			'placeholder', 'readonly', 'required', 'rows', 'wrap',
		],
		'select' => [
			'autofocus', 'disabled', 'multiple', 'name', 'required', 'size',
		],
	];

	protected $flat_attributes = [
		'required', 'disabled', 'readonly'
	];

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
	public function __get(string $attribute){
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
	 * Output all attributes as a string
	 *
	 * @return string
	 */
	public function getString(){
		$output = [];
		foreach($this->attributes as $key => $value)
		{
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
	protected function isValidAttribute($key)
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
