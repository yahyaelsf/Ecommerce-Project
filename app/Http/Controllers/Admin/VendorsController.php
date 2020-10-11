<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Vendor;
use Illuminate\Http\Request;
use App\MainCategory;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\Notification;
use DB;

class VendorsController extends Controller
{
    use Notifiable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::selection()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MainCategory::where('translation_of', '0')->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {
        try {
            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }
            if (!$request->has('active')) {
                $vendor= Vendor::create(array_merge( [
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'active' => '0',
                    'address' => $request->address,
                    'logo' => $filePath,
                    'password' => $request->password,
                    'category_id' => $request->category_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,


                ]));
                return redirect()->route('admin.vendors')->with('success', 'تم الحفظ بنجاح');
            }
            else{
                $vendor = Vendor::create([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'active' => $request->active,
                    'address' => $request->address,
                    'logo' => $filePath,
                    'password' => $request->password,
                    'category_id' => $request->category_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                return redirect()->route('admin.vendors')->with('success', 'تم الحفظ بنجاح');
            }



            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }




    public function edit($id)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();

            return view('admin.vendors.edit', compact('vendor', 'categories'));

        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);


            DB::beginTransaction();
            //photo
            if ($request->has('logo') ) {
                $filePath = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)
                    ->update([
                        'logo' => $filePath,
                    ]);
            }
            $data = $request->except('_token', 'id', 'logo', 'password');
            if ($request->has('password') && !is_null($request->  password)) {

                $data['password'] = $request->password;
            }

            if (!$request->has('active')) {
                $vendor->update(array_merge($data, ['active' => '0']));
                return redirect()->route('admin.languages')->with('success', 'تم تحديث اللغة بنجاح');
            }
            else{
                $vendor->update($data);
                return redirect()->route('admin.languages')->with('success', 'تم تحديث اللغة بنجاح');
            }



            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $exception) {
            return $exception;
            DB::rollback();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){


    }
}
