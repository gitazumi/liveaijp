<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Sidebar extends Component
{
    public function render(): View|string
    {
        return view('layouts.sidebar');
    }
}