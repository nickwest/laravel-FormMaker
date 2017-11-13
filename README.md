Laravel FormMaker
=================

Laravel FormMaker was created by and is maintained by [Nick West](https://github.com/nickwest). It was created to minimize the time and effort required to create simple HTML forms to support Eloquent Model CRUD functionality.

Laravel FormMaker includes a trait for Eloquent models that can be used to automatically generate a form view based on the underlying table structure supporting the Eloquent model. Forms created this way can be fully customized. Basic validation is included and can be expanded upon easily.

As of version 5.5 Laravel FormMaker can be easily themed to support various front-end css/js frameworks and includes the necessary blade templates to support the [Bulma](https://bulma.io) framework out of the box.

## Installation

Laravel FormMaker requires PHP 7+. This particular version supports Laravel 5.5. It might function on older versions, but it was not tested on < 5.5.

To get the latest version you need only require the package via Composer.
```
Composer require nickwest/form-maker
```
FormMaker supports [Laravel Auto-Discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518). It shouldn't be necessary with Laravel 5.5+, but to manually add the package the following providers should be added in your config/app.php:
```
Nickwest\FormMaker\FormMakerServiceProvider::class,
Nickwest\FormMaker\bulma\FormMakerBulmaThemeServiceProvider::class
```
## Usage

### Adding functionality to an Eloquent Model
```
class Sample extends Model{
    use FormTrait;

	protected $table = 'sample';

	// Options for setting up the form:

	// OPTION 1: Create a method in the model that will be called by the controller

	// The method name doesn't matter, it's up to you.
	public function prepareForm()
    {
        // Set a default Form Field label postfix
        $this->label_postfix = ':';

	    // This comes from the FormTrait. It generates form field data by looking at the model's table columns
        $this->generateFormData();

		// Use a custom theme for the generated markup
	    $this->Form()->setTheme(new \Nickwest\FormMaker\bulma\Theme());

        // By Default all fields will be displayed

        // We can set specific fields to be displayed by the form. The order here dictates the order the fields will show on the form
        // $this->Form()->setDisplayFields( array(
        //     'email',
        //     'attendance_date',
        //     'placard_color',
        //     'photograph',
        //     'phone_number',
        //     'link_to_website',
        //     'meal_choice',
        //     'fruits_they_like',
        //     'plus_one',
        // ));

        // Or we can remove only selected display fields and the rest will display in the same order they appear in the underlying table.
        $this->Form()->removeDisplayFields([
            'id',
            'created_at',
            'updated_at',
        ]);

        // Set field types for fields that aren't just default types
	    $this->Form()->setTypes([
            'email' => 'email',
            'attendance_date' => 'date',
            'placard_color' => 'color',
			'photograph' => 'file',
			'phone_number' => 'tel',
			'link_to_website' => 'url',
			'meal_choice' => 'select',
            'fruits_they_like' => 'checkbox',
            'plus_one' => 'radio', // Yes/No enum in database
		]);

		// Fields set up as Enums in the database will automatically have enum options available, but enums are not requires for multiple choice fields

        $this->Form()->fruits_they_like->setOptions([
            'banana' => 'Banana',
            'strawberry' => 'Strawberry',
            'apple' => 'Apple',
            'mango' => 'Mango',
            'passion Fruit' => 'Passion Fruit',
            'orange' => 'Orange',
            'kiwi' => 'Kiwi',
            'pear' => 'Pear',
            'pineapple' => 'Pineapple'
        ]);

        $this->Form()->meal_choice->setOptions([
            'cheap_meal' => 'Chicken',
            'average_meal' => 'Beef',
            'expensive_meal' => 'Lobster',
		]);

		// Make it a multi-select field
        $this->Form()->meal_choice->multiple = true;

		// Set examples to show up below the field
		$this->Form()->setExamples([
            'email' => 'ex: example@example.com',
        ]);

		// Set a pattern (used to add pattern="" attribute to field)
        $this->Form()->phone_number->attributes->pattern = '\\d{3}[\\-]\\d{3}[\\-]\\d{4}';

	    // Set a placeholder (similar to example, but uses placeholder attribute)
        $this->Form()->phone_number->attributes->placeholder = '123-456-7890';

		// Set some validation (This uses Laravel's Validator facade: https://laravel.com/docs/5.5/validation)
		$this->validation_rules = [
            'email' => 'email|max:256',
            'fruits_they_like' => 'in:'.implode(',', array_keys($this->Form()->fruits_they_like->options)),
            'meal_choice' => 'in:'.implode(',', array_keys($this->Form()->meal_choice->options)),
            //... you can add an validation rules you want here.
        ];

		// Include a delete button?
        $this->Form()->allow_delete = false;



	}

	// OPTION 2: Do all of this in the controller. You would choose this method if your form is likely to look different at different routes. If it will always be the same having a method to call is less repetition. You can also modify the form after calling the method above, so a mix of the two works as well.

	// Option 3: Do this in the constructor. I would advise against doing this in the constructor since generateFormData() runs a query to the get the table structure.

}
```
### The Controller

```
	// The GET route for the form page
    public function getForm(Request $request, int $id=0)
    {
        $blade_data = [
            'page_title' => 'Sample Form',
            'whatever_else' => 'Any data your base view need can be passed through',
        ];

		// Find the Model if an ID is passed in, otherwise start a new one.
        $Sample = Sample::findOrNew($id);

		// prepare the form as described in the Model method above
        $Sample->prepareForm();

		// This is a FormMaker trait method. It populates form values based on data in the current Model and by defaults and other settings configured in the step before.
        $Sample->setAllFormValues();

		// If there's old request data, set it.
        if($request->old())
        {
            $Sample->setPostValues($request->old());
        }

		// Make a view that extends 'base_layout'
        return $Sample->getFormView($blade_data, 'base_layout', 'content');
    }
```

## Misc

FormMaker doesn't need to be used by the trait. The Form and Field classes can be used standalone as well. Instead of automatically generating form fields, they will need to be manually declared.
