<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

// 追加
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',

        //追加
        'main_category_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id'); // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id){
        return PostComment::where('post_id', $post_id)->count();
        //return Post::with('postComments')->find($post_id)->postComments();
    }
}
