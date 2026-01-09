<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kaster\Cms\Database\Factories\PageFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Kaster\Cms\Enums\PageStatus;
use Kaster\Cms\Enums\PageTheme;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $lang
 * @property string $status
 * @property ArrayObject $content
 * @property string|null $searchable_content
 * @property ArrayObject|null $seo_metadata
 * @property bool $is_landing
 * @property string $theme
 * @property int|null $parent_id
 * @property int|null $translation_origin_id
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * * @property-read Page|null $parent
 * @property-read Page|null $translationOrigin
 * @property-read Collection<int, Page> $translations
 * @property-read string $meta_title_resolved
 * @property-read Media|null $og_image_resolved
 */
class Page extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'pages';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_landing' => 'boolean',
        'content' => AsArrayObject::class,
        'seo_metadata' => AsArrayObject::class,
        'status' => PageStatus::class,
        'theme' => PageTheme::class,
    ];

    protected static function booted(): void
    {
        //        static::saving(static function (Page $page) {
//            if ($page->isDirty('content')) {
//                $page->searchable_content = $page->generateSearchableText();
//            }
//
//            $page->lang = $page->lang ?? 'pt_BR';
//        });
    }

    protected function generateSearchableText(): string
    {
        if (empty($this->content)) {
            return '';
        }

        $rawText = json_encode($this->content);

        $cleanText = str_replace(['"', '{', '}', '[', ']', 'type:', 'data:'], ' ', $rawText);

        return strip_tags($cleanText);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function translationOrigin(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'translation_origin_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(__CLASS__, 'translation_origin_id');
    }

    public function translationForLang(string $locale): Page
    {
        return $this->translations->where('lang', $locale)->firstOrFail();
    }

    protected function ogImageResolved(): Attribute
    {
        return Attribute::make(
            get: function () {
                $mediaId = $this->seo_metadata['og_image_id'] ?? null;

                if (!$mediaId) {
                    return null;
                }

                return Media::find($mediaId);
            }
        );
    }

    public function fullUrlPath(): string
    {
        $path = '';

        $parent = $this->parent;
        $parentsPath = '';
        while ($parent) {
            if ($parent->slug !== 'index') {
                $parentsPath = $parent->slug . '/' . $parentsPath;
            }

            $parent = $parent->parent;
        }

        $path .= $parentsPath;
        if ($this->slug !== 'index') {
            $path .= $this->slug;
        }

        return $path;
    }

    public function url(): string
    {
        return url($this->fullUrlPath());
    }

    public function getMenuLabel(): string
    {
        return $this->title;
    }

    public function isPublished(): bool
    {
        return $this->status === PageStatus::Published;
    }

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }
}
