<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $name;
    public $value;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $name
     * @param string|null $value
     */
    public function __construct(string $type, string $name, string $value=null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.forms.input');
    }
}
