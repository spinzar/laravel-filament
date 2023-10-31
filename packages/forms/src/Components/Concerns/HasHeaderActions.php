<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Arr;

trait HasHeaderActions
{
    /**
     * @var array<Action | Closure>
     */
    protected array $headerActions = [];

    /**
     * @var array<Action> | null
     */
    protected ?array $cachedHeaderActions = null;

    /**
     * @param  array<Action | Closure>  $actions
     */
    public function headerActions(array $actions): static
    {
        $this->headerActions = [
            ...$this->headerActions,
            ...$actions,
        ];

        return $this;
    }

    /**
     * @return array<Action>
     */
    public function getHeaderActions(): array
    {
        return $this->cachedHeaderActions ?? $this->cacheHeaderActions();
    }

    /**
     * @return array<Action>
     */
    public function cacheHeaderActions(): array
    {
        $this->cachedHeaderActions = [];

        foreach ($this->headerActions as $headerAction) {
            foreach (Arr::wrap($this->evaluate($headerAction)) as $action) {
                $this->cachedHeaderActions[$action->getName()] = $this->prepareAction(
                    $action
                        ->defaultColor('gray')
                        ->defaultSize(ActionSize::Small)
                        ->defaultView(Action::ICON_BUTTON_VIEW),
                );
            }
        }

        return $this->cachedHeaderActions;
    }
}
