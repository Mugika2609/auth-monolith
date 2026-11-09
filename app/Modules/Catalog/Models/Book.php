<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\ValueObjects\ISBN;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'isbn', 'published_year', 'price', 'stock', 'author_id', 'publisher_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    // ISBN divalidasi dan dinormalkan lewat value object saat disimpan
    protected function isbn(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ISBN::from($value)->value(),
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    // Many-to-many dengan pivot berkolom added_at
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category')->withPivot('added_at');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $q) use ($term) {
            $q->where(function (Builder $w) use ($term) {
                $w->where('title', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%");
            });
        });
    }

    public function scopeInCategory(Builder $query, ?int $categoryId): Builder
    {
        return $query->when($categoryId, function (Builder $q) use ($categoryId) {
            $q->whereHas('categories', fn (Builder $c) => $c->where('categories.id', $categoryId));
        });
    }
}