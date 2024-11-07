<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories(){
        return $this->hasMany('App\Models\Categories\SubCategory');
        //return $this->hasMany(SubCategory::class, 'sub_category',);// リレーションの定義
        // main_category_id 以外の外部キーに（sub_category を外部キーに指定）したい時
    }

}
