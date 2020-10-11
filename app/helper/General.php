<?php
use Illuminate\Support\Facades\Config;

function get_Languages(){
    return \App\Language::active() -> Selection() -> get();
}
function get_default_lang(){
    return Config::get('app.locale');
}
function uploadImage($folder , $image){
    $image ->store('/', $folder);
    $file_name = $image->hashName();
    $path = 'images/'.$folder.'/'.$file_name;
    return $path;
}
