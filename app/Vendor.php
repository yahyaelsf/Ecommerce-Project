<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'name', 'logo','mobile','password','email','address','active','category_id','created_at','updated_at',
    ];
    protected $hidden = ['category_id','password'];
    public function scopeActive($query)
    {
        return $query -> where('active','1');
    }
    public function scopeSelection($query)
    {
        return $query -> select('id','name', 'logo','mobile','latitude','longitude','email','address','active','category_id');

    }
    public function getActive(){
        return $this->active == 1 ? 'فعال': 'غير فعال';
    }
    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }
    public function category()
    {

        return $this->belongsTo('App\MainCategory', 'category_id', 'id');
    }


    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
