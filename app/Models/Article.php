<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Article extends Model
{
    use HasFactory, Sluggable, HasEagerLimit;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'body',
        'short_description',
        'views',
        'likes',
        'dislikes',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
