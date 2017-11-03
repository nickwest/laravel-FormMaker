<?php namespace Nickwest\FormMaker;

// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

trait TableTrait{
    /**
     * Table object see Nickwest\FormMaker\Table
     *
     * @var Table
     */
    protected $TableObject = null;

    /**
     * Boot the trait. Adds an observer class for form
     *
     * @return Table
     */
    public function Table()
    {
        if(!is_object($this->TableObject))
        {
            $this->TableObject = new Table();
        }

        return $this->TableObject;
    }

    /**
     * Make a table View and return the rendered output
     *
     * @param Illuminate\Database\Eloquent\Collection $blade_data
     * @param string $extends
     * @param string $section
     * @return View
     */
    public function getTableView(Collection &$Collection, array $blade_data, string $extends = '', string $section = '')
    {
        $data = [];
        foreach($Collection as $Item)
        {
            $row = [];
            foreach($this->TableObject->getDisplayFields() as $field_name)
            {
                $row[$field_name] = $Item->$field_name;
            }
            $data[] = $row;
        }
        $this->TableObject->setData($data);

        return $this->TableObject->makeview($blade_data, $extends, $section);
    }


}

?>
