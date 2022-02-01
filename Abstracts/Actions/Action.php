<?php

declare(strict_types=1);

namespace Apiato\Core\Abstracts\Actions;

use Apiato\Core\Traits\HasRequestCriteriaTrait;

abstract class Action
{
    use HasRequestCriteriaTrait;

    /**
     * Set automatically by the controller after calling an Action.
     * Allows the Action to know which UI invoke it, to modify its behaviour based on it, when needed.
     */
    protected string $ui;

    /**
     * Made actions invocable.
     */
    public function __invoke(...$arguments)
    {
        return static::run(...$arguments);
    }

    public function getUI(): string
    {
        return $this->ui;
    }

    public function setUI(string $interface): static
    {
        $this->ui = $interface;

        return $this;
    }
}
