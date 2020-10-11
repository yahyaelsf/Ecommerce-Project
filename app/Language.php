<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'abdr', 'locale','name','direction','active','created_at','updated_at',
    ];
    public function scopeActive($query)
    {
        return $query -> where('active','1');
    }
    public function scopeSelection($query)
    {
        return $query -> select('id','abdr','name','direction','active');
    }
 public function getActive(){
        return $this->active == 1 ? 'فعال': 'غير فعال';
 }
}
