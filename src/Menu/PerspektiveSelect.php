<?php

namespace NormanHuth\NovaPerspectives\Menu;

use JsonSerializable;
use Laravel\Nova\AuthorizedToSee;
use Laravel\Nova\Makeable;

/**
 * @method static static make(string|null $label = null)
 */
class PerspektiveSelect implements JsonSerializable
{
    use AuthorizedToSee;
    use Makeable;

    /**
     * The Element label.
     *
     * @var string|null
     */
    protected ?string $label;

    /**
     * Construct a new PerspektiveSelect instance.
     */
    public function __construct(?string $label = null)
    {
        $this->label = $label;
    }

    /**
     * The menu's component.
     *
     * @var string
     */
    public string $component = 'perspektive-select';

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'key' => md5('PerspektiveSelect'),
            'component' => $this->component,
            'label' => $this->label,
            'isCustom' => true,
        ];
    }
}
