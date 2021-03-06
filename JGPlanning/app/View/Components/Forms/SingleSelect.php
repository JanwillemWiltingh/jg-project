<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SingleSelect extends Component
{
    public $array;
    public $fields;
    public $name;
    public $value;
    public $default;
    public $capitalize;
    public $disabled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($array, array $fields, string $name, int $value=null, string $default=null, bool $capitalize=false, bool $disabled=false)
    {
        $this->array = $array;
        $this->fields = $fields;
        $this->name = $name;
        $this->value = $value;
        $this->default = $default;
        $this->capitalize = $capitalize;
        $this->disabled = $disabled;
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
