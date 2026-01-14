<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Kaster\Cms\Enums\LinkItemTarget;

/**
 * @property int $id
 * @property int $link_group_id
 * @property int|null $parent_id
 * @property int|null $order
 * @property string $label
 * @property string|null $url
 * @property LinkItemTarget $target
 * @property-read LinkGroup $linkGroup
 * @property-read Model|null $model
 * @property-read LinkItem|null $parent
 * @property-read Collection<int, LinkItem> $children
 * @property-read bool $is_dropdown
 * @property-read bool $has_children
 */
class LinkItem extends Model
{
    use HasFactory;

    protected $table = 'link_items';

    protected $casts = [
        'target' => LinkItemTarget::class,
    ];

    protected $fillable = [
        'link_group_id',
        'parent_id',
        'order',
        'label',
        'url',
        'target',
    ];

    /**
     * Whether this item acts as a dropdown (has child items).
     */
    protected function isDropdown(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->children()->exists()
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
     * Optional linked model (e.g., Page) - when selected, auto-populates URL.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function linkGroup(): BelongsTo
    {
        return $this->belongsTo(LinkGroup::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->orderBy('order');
    }

    protected static function newFactory(): \Kaster\Cms\Database\Factories\LinkItemFactory
    {
        return \Kaster\Cms\Database\Factories\LinkItemFactory::new();
    }
}
