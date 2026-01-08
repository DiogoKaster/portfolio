<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Kaster\Cms\Enums\MenuItemTarget;

/**
 * @property int $id
 * @property string|null $label
 * @property string|null $custom_url
 * @property MenuItemTarget $target
 * @property string $type
 * @property-read Menu $menu
 * @property-read null $model
 * @property-read Collection<int, MenuItem> $children
 */
class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'target' => MenuItemTarget::class,
    ];

    protected $fillable = [
        'menu_id',
        'label',
        'custom_url',
        'target',
        'type',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}
