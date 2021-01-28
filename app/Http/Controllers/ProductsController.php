<?php

namespace App\Http\Controllers;

use App\accounting;
use App\CategoryProducts;
use App\Credit;
use App\Debit;
use App\Financials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\historyPeople;
use App\Materials;
use App\product_category;
use App\ProductHPP;
use App\ProductMaterials;
use App\products;
use Exception;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Produk';
        return view('core/product', compact('title'));
    }

    //Kategori
    public function category()
    {
        $title = 'Kategori Produk';
        $categories = json_decode(CategoryProducts::select('id')->addSelect('name')->addSelect('image')->addSelect('created_at')->addSelect('created_by')->addSelect('description')->get(), true);
        return view('core/categoryProducts', compact('title', 'categories'));
    }
    public function createCategory()
    {
        $title = 'Kategori Produk';
        return view('core/createCategory', compact('title'));
    }

    public function storeCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $name = 'nophoto.png';
        if(!is_null($request->file('imgCategory'))){
            $file = $request->file('imgCategory');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/category/', $name);
        }
        CategoryProducts::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name,
            'created_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Produk',
            'description' => 'Menambah Kategori Produk',
            'icon' => 'fas fa-box',
            'ip_address' => $request->ip()
        ]);
        return redirect('/category')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Kategori produk Sudah Ditambah!</div>');exit;
    }

    public function showcategory($id)
    {
        $title = 'Kategori Produk';
        $category = CategoryProducts::select('id')->addSelect('name')->addSelect('description')->addSelect('image')->addSelect('created_by')->addSelect('created_at')->where('id', $id)->first();
        return view('core/showCategory', compact('title', 'category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $oldImage = CategoryProducts::select('image')->where('id', $id)->first();
        $name = $oldImage->image;
        if(!is_null($request->file('imgCategory'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/category/' . $oldImage->image);
                }
            }
            unset($oldImage);
            $file = $request->file('imgCategory');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/category/', $name);
        }

        $affected = CategoryProducts::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name,
            'updated_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Produk',
            'description' => 'Mengubah Kategori Produk id = '. $id,
            'icon' => 'fas fa-box',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/category')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Kategori produk Sudah Diubah!</div>');exit;
        } else {
            return redirect('/category')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Kategori produk gagal Diubah!</div>');exit;
        }
    }

    public function destroyCategory(Request $request, $id)
    {
        if(Auth::user()->roles == 1){
            $category = CategoryProducts::select('id')->addSelect('image')->where('id', $id)->first();
            if(!is_null($category->image)){
                if($category->image !== 'nophoto.png'){
                    File::delete(public_path() . '/img/category/' . $category->image);
                }
                CategoryProducts::where('id', $id)->update(['deleted_by'=> Auth::user()->id]);
                $affected = CategoryProducts::where('id', $id)->delete();
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Produk',
                    'description' => 'Menghapus Kategori Produk id = '. $id,
                    'icon' => 'fas fa-box',
                    'ip_address' => $request->ip()
                ]);
                if($affected == 1){
                    return redirect('/category')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Kategori produk Sudah Dihapus!</div>');exit;
                } else {
                    return redirect('/category')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Kategori produk gagal Dihapus!</div>');exit;
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

    //Produk
    public function products()
    {
        $title = 'Sub Kategori Produk';
        $products = json_decode(products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('price')->get(), true);
        return view('core/products', compact('title', 'products'));
    }
    public function createproducts()
    {
        $title = 'Sub Kategori Produk';
        $categories = json_decode(CategoryProducts::select('id', 'name')->get(), true);
        $materials = json_decode(Materials::select('id')->addSelect('name')->get(), true);
        return view('core/createproducts', compact('title', 'categories', 'materials'));
    }

    public function storeproducts(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required',
            'price' => 'required',
            'materials' => 'required'
        ]);
        $price = str_replace(',', '', $request->price);
        $name = '';
        if(!is_null($request->file('imgproducts'))){
            $file = $request->file('imgproducts');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/products/', $name);
        }
        $product = new products();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->cover = $name;
        $product->price = $price;
        $product->stock = 0;
        $product->created_by = Auth::user()->id;
        $product->save();
        $product_id = $product->id;
        ProductHPP::create(['id_product' => $product->id, 'hpp' => 0]);
        for($i=0;$i < count($request->materials); $i++){
            ProductMaterials::create([
                'id_product' => $product_id,
                'id_material' => $request->materials[$i],
                'unit' => 0
            ]);
        }
        product_category::create([
            'product_id' => $product_id,
            'category_id' => $request->category
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Produk',
            'description' => 'Menambah Informasi Produk',
            'icon' => 'fas fa-boxes',
            'ip_address' => $request->ip()
        ]);
        return redirect('/products')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Informasi produk Sudah Ditambah!</div>');exit;
    }

    public function showproducts($id)
    {
        $title = 'Sub Kategori Produk';
        $products = products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('created_by')->addSelect('created_at')->addSelect('price')->where('id', $id)->first();
        $materials = json_decode(Materials::select('id')->addSelect('name')->get(), true);
        $sc = product_category::select('category_id')->where('product_id', $id)->first();
        $sm = ProductMaterials::select('id_material')->where('id_product', $id)->get();
        $categories = json_decode(CategoryProducts::select('id')->addSelect('name')->get(), true);
        return view('core/showproducts', compact('title', 'products', 'categories', 'materials', 'sc', 'sm'));
    }

    public function updateproducts(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required',
            'price' => 'required',
            'materials' => 'required'
        ]);
        $price = str_replace(',', '', $request->price);
        $oldImage = products::select('cover')->where('id', $id)->first();
        $name = $oldImage->cover;
        if(!is_null($request->file('imgproducts'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/products/' . $oldImage->cover);
                }
            }
            unset($oldImage);
            $file = $request->file('imgproducts');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/products/', $name);
        }
        ProductMaterials::where('id_product', $id)->delete();
        for($i=0;$i < count($request->materials); $i++){
            ProductMaterials::create([
                'id_product' => $id,
                'id_material' => $request->materials[$i],
                'unit' => 0
            ]);
        }
        $affected = products::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'cover' => $name,
            'price' => $price,
            'updated_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Produk',
            'description' => 'Mengubah Informasi Produk id = '. $id,
            'icon' => 'fas fa-boxes',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/products')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Informasi produk Sudah Diubah!</div>');exit;
        } else {
            return redirect('/products')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Informasi produk gagal Diubah!</div>');exit;
        }
    }

    public function destroyproducts(Request $request, $id)
    {
        if(Auth::user()->roles == 1){
            $products = products::select('id')->addSelect('cover')->where('id', $id)->first();
            if(!is_null($products->cover)){
                if($products->cover !== 'nophoto.png'){
                    File::delete(public_path() . '/img/products/' . $products->cover);
                }
                product_category::where('product_id', $id)->delete();
                products::where('id', $id)->update(['deleted_by'=> Auth::user()->id]);
                ProductMaterials::where('id_product', $id)->delete();
                $affected = products::where('id', $id)->delete();
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Produk',
                    'description' => 'Menghapus Informasi Produk id = '. $id,
                    'icon' => 'fas fa-boxes',
                    'ip_address' => $request->ip()
                ]);
                if($affected == 1){
                    return redirect('/products')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, informasi produk Sudah Dihapus!</div>');exit;
                } else {
                    return redirect('/products')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, informasi produk gagal Dihapus!</div>');exit;
                }
            }
        }
    }

    //producted - Barang Mentah ke Barang Dagang
    public function producted()
    {
        $title = 'Informasi Produk';
        $products = json_decode(products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('price')->addSelect('stock')->get(), true);
        return view('core/producted', compact('title', 'products'));
    }
    public function showproducted($id)
    {
        $title = 'Detail Informasi Produk';
        $products = products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('created_by')->addSelect('created_at')->addSelect('price')->where('id', $id)->first();
        $pm = ProductMaterials::select('materials.name')->addSelect('materials.id')->addSelect('material_product.unit')->addSelect('material_unit.unit as price')->join('materials', 'materials.id', 'material_product.id_material')->join('material_unit', 'materials.id', 'material_unit.id_material')->where('id_product', $id)->get();
        $categories = json_decode(CategoryProducts::select('id')->addSelect('name')->get(), true);
        return view('core/showproducted', compact('title', 'products', 'pm'));
    }
    public function showallproducted($id)
    {
        $title = 'Detail Informasi Produk';
        $products = products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('created_by')->addSelect('created_at')->addSelect('price')->where('id', $id)->first();
        $pm = ProductMaterials::select('materials.name')->addSelect('materials.id')->addSelect('material_product.unit')->addSelect('material_unit.unit as price')->addSelect('material_product.unit')->join('materials', 'materials.id', 'material_product.id_material')->join('material_unit', 'materials.id', 'material_unit.id_material')->where('id_product', $id)->get();
        $total = 0;
        for($i=0;$i<count($pm);$i++){ $total += (int)$pm[$i]['price'] * (int)$pm[$i]['unit']; }
        $categories = json_decode(CategoryProducts::select('id')->addSelect('name')->get(), true);
        return view('core/productedDetails', compact('title', 'products', 'pm', 'total'));
    }
    public function storeproducted(Request $request, $id)
    {
        $this->validate($request, [
            'unit' => 'required'
        ]);
        $unit = $request->unit;
        $unitKey = array_keys($unit);
        $unitVal = array_values($unit);unset($unit);
        for($i=0;$i < count($unitKey);$i++)
        {
            ProductMaterials::where('id_product', $id)->where('id_material', $unitKey[$i])->update(['unit' => $unitVal[$i]]);
        }

        $pm = ProductMaterials::select('materials.name')->addSelect('materials.id')->addSelect('material_product.unit')->addSelect('material_unit.unit as price')->addSelect('material_product.unit')->join('materials', 'materials.id', 'material_product.id_material')->join('material_unit', 'materials.id', 'material_unit.id_material')->where('id_product', $id)->get();
        $total = 0;for($i=0;$i<count($pm);$i++){ $total += (int)$pm[$i]['price'] * (int)$pm[$i]['unit']; }
        ProductHPP::where('id_product', $id)->update(['hpp' => $total]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Produk',
            'description' => 'Menerapkan Informasi Produk',
            'icon' => 'fas fa-boxes',
            'ip_address' => $request->ip()
        ]);
        return redirect('/producted')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Informasi produk Sudah Diterapkan!</div>');exit;
    }
    //Stok Produk
    public function stock()
    {
        $title = 'Stok Produk';
        $products = json_decode(products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('price')->addSelect('stock')->get(), true);
        $materials = Materials::select('id')->addSelect('name')->addSelect('stock')->addSelect('is_rewrite')->get();
        $relation = ProductMaterials::select('id_product')->addSelect('id_material')->addSelect('unit')->get();
        return view('core/stockproduct', compact('title', 'products', 'materials', 'relation'));
    }
    public function restock(Request $request)
    {
        if(Auth::user()->roles == 1){
            try{
                $data = explode('&', $request->data);
                $stock = explode('=', $data['0']);
                $stock = (int)$stock['1'];
                $id = explode('=', $data['1']);
                $id = (int)$id['1'];

                $relation = json_decode(ProductMaterials::select('material_product.id_material')->addSelect('material_unit.unit as price')->addSelect('materials.stock')->addSelect('material_product.unit')->addSelect('materials.is_rewrite')->join('materials', 'materials.id', 'material_product.id_material')->join('material_unit', 'material_product.id_material', 'material_unit.id_material')->where('id_product', $id)->get(), true);
                $oldStock = products::select('stock')->where('id', $id)->first();
                $nowStock = $stock -$oldStock->stock;


                for($i=0;$i < count($relation);$i++)
                {
                    if($nowStock * $relation[$i]['unit'] > $relation[$i]['stock'])
                    {
                        echo json_encode(["error" => true, "messsage" => "Stok Tidak Memenuhi"]);exit;
                    }
                }

                $total = 0;$cond = 0;
                for($i=0;$i < count($relation); $i++){
                    if(((int)$relation[$i]['stock'] - ((int)$nowStock)) >= 0){
                        $total += ((int)$nowStock * $relation[$i]['unit']) * $relation[$i]['price'];
                        if($relation[$i]['is_rewrite'] == 1 && $nowStock > 0){
                            Materials::where('id', $relation[$i]['id_material'])->update(['stock' => ((int)$relation[$i]['stock'] - ((int)$nowStock * $relation[$i]['unit']))]);$cond = 1;
                        } if($relation[$i]['is_rewrite'] == 1 && $nowStock < 0){
                            Materials::where('id', $relation[$i]['id_material'])->update(['stock' => ((int)$relation[$i]['stock'] - ((int)$nowStock * $relation[$i]['unit']))]);$cond = 2;
                        }
                    } else {
                        echo json_encode(["error" => true, "messsage" => "Stok Tidak Memenuhi"]);exit;
                    }
                }
                if($cond == 1){
                    $debit = new Debit();$debit->refdebit=102;$debit->debitdesc=$total;$debit->save();
                    $credit = new Credit();$credit->refcredit=117;$credit->creditdesc=$total;$credit->save();
                    $financial = new Financials();$financial->id_debit=$debit->id;$financial->id_credit=$credit->id;$financial->description='Menambah Pembuatan Barang Dagang';$financial->created_by=Auth::user()->id;$financial->save();
                    $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', 102)->orWhere('ref', '=', 117)->get(), true);
                    accounting::where('ref', 102)->update(['description' => ($acc['0']['description'] + $total)]);
                    accounting::where('ref', 117)->update(['description' => ($acc['1']['description'] - $total)]);
                } else if($cond == 2) {
                    $debit = new Debit();$debit->refdebit=117;$debit->debitdesc=-($total);$debit->save();
                    $credit = new Credit();$credit->refcredit=102;$credit->creditdesc=-($total);$credit->save();
                    $financial = new Financials();$financial->id_debit=$debit->id;$financial->id_credit=$credit->id;$financial->description='Membatalkan Pembuatan Barang Dagang';$financial->created_by=Auth::user()->id;$financial->save();
                    $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', 102)->orWhere('ref', '=', 117)->get(), true);
                    accounting::where('ref', 102)->update(['description' => ($acc['0']['description'] + $total)]);
                    accounting::where('ref', 117)->update(['description' => ($acc['1']['description'] - $total)]);
                } else {echo json_encode(["error" => true, "messsage" => "Aktifkan Pengurangan Bahan Mentah Terkait"]);exit;;exit; }

                $affected = products::where('id', $id)->update(['stock' => $stock]);
                if($affected == 1){
                    return 1;exit;
                } else {
                    echo json_encode(["error" => true, "messsage" => "Stok Tidak Memenuhi"]);exit;;exit;
                }
            } catch(Exception $e){
                echo json_encode(['error' => true, 'message' => 'Error: '.$e]);exit;
            }
        }
    }
}
