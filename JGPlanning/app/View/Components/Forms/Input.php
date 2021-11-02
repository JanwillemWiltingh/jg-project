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
    public $disabled;
    public $readonly;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $name
     * @param string|null $value
     * @param bool $disabled
     * @param bool $readonly
     */
    public function __construct(string $type, string $name, string $value=null, bool $disabled=false, bool $readonly=false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
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
