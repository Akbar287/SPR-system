<?php

namespace App\Http\Controllers;

use App\historyPeople;
use App\Materials;
use App\MaterialUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MaterialController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Bahan Mentah';
        $materials = Materials::select('id')->addSelect('name')->addSelect('stock')->addSelect('is_rewrite')->addSelect('is_active')->get();
        return view('core/materials', compact('title', 'materials'));
    }

    public function create()
    {
        $title = 'Tambahkan Bahan Mentah';
        return view('core/createMaterial', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:64',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        $name = 'nophoto.png';
        $price = str_replace(',', '', $request->price);
        if(!is_null($request->file('imgmaterial'))){
            $file = $request->file('imgmaterial');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/material/', $name);
        }
        $material = new Materials();
        $material->name = $request->name;
        $material->description = $request->description;
        $material->stock = $request->stock;
        $material->image = $name;
        $material->is_active = (!is_null($request->is_active)) ? 1 : 0;
        $material->is_rewrite = (!is_null($request->is_rewrite)) ? 1 : 0;
        $material->created_by = Auth::user()->id;
        $material->save();

        MaterialUnit::create([
            'id_material' => $material->id,
            'unit' => $price,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Bahan Mentah',
            'description' => 'Mendaftarkan Bahan Mentah dengan id = ' . $material->id,
            'icon' => 'fas fa-luggage-cart',
            'ip_address' => $request->ip()
        ]);
        return redirect('/materials')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Bahan Mentah telah didaftarkan!</div>');exit;
    }

    public function show($id)
    {
        $title = 'Detail Bahan Mentah';
        $material = Materials::select('materials.id')->addSelect('name')->addSelect('description')->addSelect('image')->addSelect('stock')->addSelect('unit as price')->addSelect('is_active')->addSelect('is_rewrite')->addSelect('created_by')->addSelect('materials.created_at')->join('material_unit', 'materials.id', 'material_unit.id_material')->where('materials.id', $id)->first();
        return view('core/showMaterial', compact('title', 'material'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:64',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        $oldImage = Materials::select('image')->where('id', $id)->first();
        $name = $oldImage->image;$price = str_replace(',', '', $request->price);
        if(!is_null($request->file('imgmaterial'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/material/' . $oldImage->image);
                }
            }
            unset($oldImage);
            $file = $request->file('imgmaterial');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/material/', $name);
        }

        $affected = Materials::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'image' => $name,
            'is_active' => (!is_null($request->is_active)) ? 1 : 0,
            'is_rewrite' => (!is_null($request->is_rewrite)) ? 1 : 0,
            'created_by' => Auth::user()->id,
        ]);
        MaterialUnit::where('id_material', $id)->update(['unit' => $price]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Bahan Mentah',
            'description' => 'Mengubah data Bahan Mentah id='.$id,
            'icon' => 'fas fa-luggage-cart',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/materials')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Bahan Mentah Sudah Diubah!</div>');exit;
        } else {
            return redirect('/materials')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Bahan Mentah gagal Diubah!</div>');exit;
        }
    }

    public function destroy(Request $request, $id)
    {
        if(Auth::user()->roles == 1){
            $material = Materials::select('id')->addSelect('image')->where('id', $id)->first();
            if(!is_null($material->image)){
                if($material->image !== 'nophoto.png'){
                    File::delete(public_path() . '/img/material/' . $material->image);
                }
                Materials::where('id', $id)->update(['deleted_by'=> Auth::user()->id]);
                $affected = Materials::where('id', $id)->delete();
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Bahan Mentah',
                    'description' => 'Menghapus Bahan Mentah id = '. $id,
                    'icon' => 'fas fa-luggage-cart',
                    'ip_address' => $request->ip()
                ]);
                if($affected == 1){
                    return redirect('/materials')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Bahan mentah Sudah Dihapus!</div>');exit;
                } else {
                    return redirect('/materials')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Bahan mentah gagal Dihapus!</div>');exit;
                }
            }
        }
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
