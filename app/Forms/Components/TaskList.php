<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Component;

class TaskList extends Component
{
    protected string $view = 'forms.components.task-list';

    public static function make(): static
    {
        return new static();
    }

    protected array $headers = [];

    protected array | Closure $items = [];

    public function headers(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function items(array | Closure $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function getItems(): array
    {
        return $this->evaluate($this->items);
    }
}
