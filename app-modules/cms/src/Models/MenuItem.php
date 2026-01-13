<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Kaster\Cms\Enums\MenuItemTarget;
use Kaster\Cms\Enums\MenuItemType;

/**
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property int|null $order
 * @property string|null $label
 * @property string|null $custom_url
 * @property MenuItemTarget $target
 * @property MenuItemType $type
 * @property-read Menu $menu
 * @property-read Model|null $model
 * @property-read MenuItem|null $parent
 * @property-read Collection<int, MenuItem> $children
 * @property-read bool $is_dropdown
 * @property-read bool $has_children
 * @property-read string|null $url
 */
class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'target' => MenuItemTarget::class,
        'type' => MenuItemType::class,
    ];

    protected $fillable = [
        'menu_id',
        'parent_id',
        'order',
        'label',
        'custom_url',
        'target',
        'type',
    ];

    /**
     * Whether this item acts as a dropdown (container for children without its own link).
     * True if type is Dropdown OR if it has children (regardless of type).
     */
    protected function isDropdown(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->type === MenuItemType::Dropdown || $this->children()->exists()
        );
    }

    /**
     * Whether this item has child items.
     */
    protected function hasChildren(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->children()->exists()
        );
    }

    /**
     * Get the resolved URL for this menu item.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                return match ($this->type) {
                    MenuItemType::Custom => $this->custom_url,
                    MenuItemType::Resource => $this->model?->url(),
                    MenuItemType::Dropdown => null,
                    default => null,
                };
            }
        );
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->orderBy('order');
    }
}
