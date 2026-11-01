<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class StxQuickActionsWidget extends Widget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
        'xl' => 4,
    ];

    protected string $view = 'filament.widgets.stx_quick_actions';
}
