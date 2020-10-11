<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\languageRequest;
use Illuminate\Http\Request;
use App\Language;
use Illuminate\Support\Facades\Lang;

class LanguagesController extends Controller
{
    public function index(){
        $langs = Language::selection()->paginate(10);
        return view('admin.lang.index', compact('langs'));
    }
    public function create(){

        return view('admin.lang.create');
    }
    public function store(languageRequest $request){
try{

    if (!$request->has('active')) {
        Language::create(array_merge($request->except('_token'), ['active' => '0']));
        return redirect()->route('admin.languages')->with('success', 'تم الحفظ بنجاح');
    }
    else{
        Language::create($request->except('_token'));
        return redirect()->route('admin.languages')->with('success', 'تم الحفظ بنجاح');
    }
}catch (\Exception $exception){
    return redirect()->route('admin.languages')->with('error','هناك خطا يرجى المحاولة فيما بعد');
}

    }
    public function edit($id){
        $lang =Language::find($id);
        if(!$lang){
            return redirect()->route('admin.languages')->with(['error'=>'هذه اللغة غير موجودة']);
        }
        return view('admin.lang.edit',compact('lang'));

}
    public function update($id , languageRequest $request){


        try {
            $langs = Language::find($id);
            if (!$langs) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجودة']);
            }
            if (!$request->has('active')) {
                $langs->update(array_merge($request->except('_token'), ['active' => '0']));
                return redirect()->route('admin.languages')->with('success', 'تم تحديث اللغة بنجاح');
            }
            else{
                $langs->update($request->except('_token'));
                return redirect()->route('admin.languages')->with('success', 'تم تحديث اللغة بنجاح');
            }

        }
        catch (\Exception $exception){
            return redirect()->route('admin.languages')->with('error','هناك خطا يرجى المحاولة فيما بعد');
        }

    }
    public function destroy($id){

        try {
            $lang = Language::find($id);
            if (!$lang) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'هذه اللغة غير موجودة']);
            }
            $lang->delete();
            return redirect()->route('admin.languages')->with('success','تم حزف اللغة بنجاح');
        }catch (\Exception $exception){
            return redirect()->route('admin.languages')->with('error','هناك خطا يرجى المحاولة فيما بعد');
        }


    }
}
