<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

// 追加
use App\Models\Posts\Post;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        return $this->belongsTo('App\Models\Categories\MainCategory', 'main_category_id'); // リレーションの定義/main_category_id追加
        //return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function posts(){
        return $this->belongsToMany(Post::class, 'post_sub_categories', 'sub_category_id', 'post_id'); // リレーションの定義
    }
}
