<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

// 追加
use App\Models\Categories\SubCategory;
use App\Models\Categories\MainCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',

        //追加
        'main_category_id', 'created_at'
    ];

    //public $timestamps = true; // 追加/タイムスタンプ自動登録
    public $timestamps = false; // 自動タイムスタンプ無効化

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id'); // リレーションの定義
    }

    // 追加/投稿に関連したメインカテゴリー表示用
    /*public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }*/
    public function mainCategory(){
        return $this->hasOneThrough(
            \App\Models\Categories\MainCategory::class, // 最終的に取得するターゲットモデル
            \App\Models\Categories\SubCategory::class, // 中間のモデル
            //'post_id',           // SubCategory テーブルの外部キー
            'id',                // SubCategoryの外部キー
            'id',                // MainCategoryの外部キー
            'sub_category_id',   // Postのサブカテゴリーの外部キー
            'main_category_id'   // SubCategoryのメインカテゴリーの外部キー
        );
    }

    // コメント数
    public function commentCounts($post_id){
        return PostComment::where('post_id', $post_id)->count(); // 件数の取得
        //return Post::with('postComments')->find($post_id)->postComments(); // コメントの詳細データを取得
    }
}
