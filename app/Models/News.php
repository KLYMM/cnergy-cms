<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // protected $table = 'news';

    protected $primaryKey = 'id';

    protected $deletedAt = ['deleted_at'];

    protected $fillable = [
        'is_headline',
        'is_home_headline',
        'is_category_headline',
        'editor_pick',
        'is_curated',
        'is_adult_content',
        'is_verify_age',
        'is_advertorial',
        'is_seo',
        'is_disable_interactions',
        'is_branded_content',
        'title',
        'slug',
        'types',
        'keywords',
        'photographers',
        'reporters',
        'contributors',
        'image',
        'synopsis',
        'description',
        'content',
        'published_at',
        'published_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'category_id',
        'description',
        'is_published'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'news_tags')->withPivot('id');
    }

    public function news_paginations(){
        return $this->hasMany(NewsPagination::class, 'news_id')->orderBy("order_by_no", "ASC");
    }

    public function news_images(){
        return $this->hasMany(PhotoNews::class, 'news_id')->orderBy("order_by_no", "ASC");
    }

    public function news_videos(){
        return $this->hasMany(VideoNews::class, 'news_id')->orderBy("order_by_no", "ASC");
    }
    
    public function news_photo(){
        return $this->hasMany(PhotoNews::class, 'news_id')->orderBy("order_by_no", "ASC");
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Log::class,
            'news_id',
            'uuid',
            'id',
            'updated_by'
        )->with('roles')->distinct();
    }
}
