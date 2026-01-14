<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string|null $position
 * @property string|null $lang
 * @property int|null $translation_origin_id
 * @property-read Collection<int, LinkItem> $items
 * @property-read Collection<int, LinkItem> $rootItems
 * @property-read LinkGroup|null $translationOrigin
 * @property-read Collection<int, LinkGroup> $translations
 */
class LinkGroup extends Model
{
    use HasFactory;

    protected $table = 'link_groups';

    protected $fillable = [
        'title',
        'position',
        'lang',
        'translation_origin_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(LinkItem::class)->orderBy('order');
    }

    public function rootItems(): HasMany
    {
        return $this->items()->whereNull('parent_id');
    }

    public function translationOrigin(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'translation_origin_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(__CLASS__, 'translation_origin_id');
    }

    protected static function newFactory(): \Kaster\Cms\Database\Factories\LinkGroupFactory
    {
        return \Kaster\Cms\Database\Factories\LinkGroupFactory::new();
    }
}
