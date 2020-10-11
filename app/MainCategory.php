<?php

namespace App;

use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_categories';

    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at',
    ];

    public function scopeActive($query)
    {
     return $query -> where('active','1');
    }

    public function scopeSelection($query)
    {
        return $query -> select('id','translation_lang','name','slug','photo','active');
    }
    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }
    public function getActive(){
        return $this->active == 1 ? 'فعال': 'غير فعال';
    }
    // get all translation categories
    public function categories()
    {
        return $this->hasMany(self::class, 'translation_of');
    }
//    public  function subCategories(){
//        return $this -> hasMany(SubCategory::class,'category_id','id');
//    }
protected static function boot(){
        parent::boot();
        MainCategory::observe(MainCategoryObserver::class);
}


    public function vendors(){

        return $this -> hasMany('App\Vendor','category_id');
    }

     }
