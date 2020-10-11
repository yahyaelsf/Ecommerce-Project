<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoriesRequest;
use App\MainCategory;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    public function index(){
        $default_lang= get_default_lang();
        $categories = MainCategory::where('translation_lang',$default_lang)->Selection()->get();
        return  response()->json($categories);
        return view('admin.maincategory.index', compact('categories'));
    }
    public function create(){

        return view('admin.maincategory.create');
    }
    public function store(MainCategoriesRequest $request){
        try{
        $main_categories  =collect($request->category);
        $filter = $main_categories ->filter(function($value , $key){
           return $value['abdr'] == get_default_lang();
        });

        $default_category = array_values($filter->all()) [0];


        $filePath = "";
        if ($request->has('photo')) {
            $filePath = uploadImage('categories', $request->photo);
        }

       DB::beginTransaction();
// تخزين حسب للغة الاساسية للموقع يعني بس رج اخزن القسم باللغة الاساسية
        $default_category_id = MainCategory::insertGetId([
            'translation_lang' => $default_category['abdr'],
            'translation_of' => 0,
            'name' => $default_category['name'],
            'slug' => $default_category['name'],
            'photo' => $filePath
        ]);

            //فلترة كل الحقول المدخلة ما عدا الحقل الخاص باللغة الاساسية للموقع
        $categories = $main_categories ->filter(function($value , $key){
            return $value['abdr'] != get_default_lang();
        });
        // اضافة باقي الحقول متعددة اللغات بداخل مصفوفة لمنع تكرار دملة الاضافة اي فقط يتم تنفيذها مرة واحدة لتحسين الاداء
        $categories_arr = [];
        foreach ($categories as $category) {
            $categories_arr[] = [
                'translation_lang' => $category['abdr'],
                'translation_of' => $default_category_id,
                'name' => $category['name'],
                'slug' => $category['name'],
                'photo' => $filePath
            ];
        }

        MainCategory::insert($categories_arr);
        DB::commit();

        return redirect()->route('admin.categories')->with(['success' => 'تم الحفظ بنجاح']);

    } catch (\Exception $ex) {
        DB::rollback();
         return redirect()->route('admin.categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
}
    }
    public function edit($mainCat_id)
    {
        //get specific categories and its translations
        $mainCategory = MainCategory::with('categories')
            ->selection()
            ->find($mainCat_id);

        if (!$mainCategory)
            return redirect()->route('admin.categories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategory.edit', compact('mainCategory'));
    }
    public function update($mainCat_id, MainCategoriesRequest $request)
    {


        try {
            $main_category = MainCategory::find($mainCat_id);

            if (!$main_category)
                return redirect()->route('admin.categories')->with(['error' => 'هذا القسم غير موجود ']);

            // update date

            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active')) {
                $main_category->update(array_merge($request->except('_token'), ['active' => '0']));
            }else{
                $main_category->update(array_merge($request->except('_token'), ['active' => '1']));

            }

            MainCategory::where('id', $mainCat_id)
                ->update([
                    'name' => $category['name'],
                ]);

            // save image

            if ($request->has('photo')) {
                $filePath = uploadImage('categories', $request->photo);
                MainCategory::where('id', $mainCat_id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }


            return redirect()->route('admin.categories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function destroy($id)
    {

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.categories')->with(['error' => 'هذا القسم غير موجود ']);

            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.categories')->with(['error' => 'لأ يمكن حذف هذا القسم  ']);
            }

            $image = Str::after($maincategory->photo, 'assets/');// عشان انا بالمودل حدطيت ميثود بضيف الرابط لكل صورة عشان هيك لازم انا اقص الرابط وهادة قصتو
            $image = base_path('assets/' . $image);
            unlink($image); //delete from folder
            $maincategory->categories()->delete(); // عشان احزف تلترجمة تعت القسم الرئيسي المحزوف
            $maincategory->delete();
            return redirect()->route('admin.categories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function changeStatus($id)
    {
        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.categories')->with(['error' => 'هذا القسم غير موجود ']);

            $status =  $maincategory -> active  == 0 ? '1' : '0';

             $maincategory -> update(['active' =>$status ]);
            return redirect()->route('admin.categories')->with(['success' => ' تم تغيير الحالة بنجاح ']);

        } catch (\Exception $ex) {
            $ex;
            return redirect()->route('admin.categories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
