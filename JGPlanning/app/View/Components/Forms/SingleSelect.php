<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SingleSelect extends Component
{
    public $array;
    public $field;
    public $name;
    public $value;
    public $capitalize;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($array, string $field, string $name, int $value=null, bool $capitalize=false)
    {
        $this->array = $array;
        $this->field = $field;
        $this->name = $name;
        $this->value = $value;
        $this->capitalize = $capitalize;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.forms.single-select');
    }
}
