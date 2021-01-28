<?php

namespace App\Http\Controllers;

use App\historyPeople;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Vendor;
use Illuminate\Support\Facades\File;

class VendorController extends Controller
{

    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Pemasok';
        $suppliers = Vendor::select('id')->addSelect('name')->addSelect('phone')->addSelect('is_active')->get();
        return view('core/vendor', compact('title', 'suppliers'));
    }

    public function create()
    {
        $title = 'Tambah Pemasok';
        return view('core/createvendor', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:128',
            'description' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $name = 'nophoto.png';
        if(!is_null($request->file('imgsupplier'))){
            $file = $request->file('imgsupplier');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/vendor/', $name);
        }
        $product = new Vendor();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->image = $name;
        $product->phone = $request->phone;
        $product->address = $request->address;
        $product->is_active = (!is_null($request->is_active)) ? 1 : 0;
        $product->created_by = Auth::user()->id;
        $product->save();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Pemasok',
            'description' => 'Menambah Pemasok',
            'icon' => 'fas fa-user-tag',
            'ip_address' => $request->ip()
        ]);
        return redirect('/supplier')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Pemasok Sudah Ditambah!</div>');exit;
    }
    public function show($id)
    {
        $title = 'Detail Pemasok';
        $supplier = Vendor::select('id')->addSelect('name')->addSelect('description')->addSelect('image')->addSelect('phone')->addSelect('address')->addSelect('is_active')->where('id', $id)->first();
        return view('core/updatevendor', compact('title', 'supplier'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:128',
            'description' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $oldImage = Vendor::select('image')->where('id', $id)->first();
        $name = $oldImage->image;
        if(!is_null($request->file('imgsupplier'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/vendor/' . $oldImage->image);
                }
            }
            unset($oldImage);
            $file = $request->file('imgsupplier');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/vendor/', $name);
        }
        $affected = Vendor::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => (!is_null($request->is_active)) ? 1 : 0,
            'updated_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Pemasok',
            'description' => 'Mengubah data Pemasok id='. $id,
            'icon' => 'fas fa-user-tag',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/supplier')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pemasok Sudah Diubah!</div>');exit;
        } else {
            return redirect('/supplier')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Pemasok gagal Diubah!</div>');exit;
        }
    }
    public function destroy($id)
    {
        //
    }
    private function imgRandom($img)
    {
        $img = (explode('.', $img));
        $ekstensi = $img[count($img) - 1];
        unset($img[count($img) - 1]);
        $img = implode('.', $img) . '.' . time() . '.' . $ekstensi;
        return $img;
    }
}
