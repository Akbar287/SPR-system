<?php

namespace App\Http\Controllers;

use App\Discount;
use App\historyPeople;
use App\products;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title ='Diskon';
        $discount = Discount::select('discount.id')->addSelect('users.name')->addSelect('users.roles')->addSelect('products.title')->addSelect('discount.sale')->join('users', 'users.id', 'discount.id_user')->join('products', 'products.id', 'discount.id_product')->orderBy('name', 'asc')->get();
        return view('core/discount', compact('title', 'discount'));
    }

    public function create()
    {
        $title ='Diskon';
        $reseller = User::select('id')->addSelect('name')->addSelect('roles')->where('roles', 2)->orWhere('roles', 3)->get();
        $products = products::select('id')->addSelect('title')->addSelect('price')->get();
        return view('core/creatediscount', compact('title', 'products', 'reseller'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'reseller' => 'required',
            'product' => 'required',
            'discount' => 'required',
        ]);
        $reseller = $this->resellerSelective($request->reseller);
        $product = ($request->product == 0) ? json_decode(products::select('id')->get(), true) : [['id' => $request->product]];
        $discount = str_replace(',', '', $request->discount);

        for($i=0; $i < count($reseller); $i++)
        {
            for($j=0;$j < count($product);$j++)
            {
                $old = Discount::select('id')->where('id_product', $product[$j]['id'])->where('id_user', $reseller[$i]['id'])->first();
                if(!empty($old)){
                    Discount::where('id', $old->id)->delete();
                }
                Discount::create([
                    'id_product' => $product[$j]['id'],
                    'id_user' => $reseller[$i]['id'],
                    'created_by' => Auth::user()->id,
                    'sale' => $discount
                ]);
            }
        }
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menambah Data Diskon',
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/discount')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Diskon Sudah Ditambah!</div>');exit;
    }

    public function destroy(Request $request, $id)
    {
        $data = Discount::select('id')->where('id', $id)->first();
        if(!empty($data))
        {
            $affected = Discount::where('id', $id)->delete();
        }
        if($affected > 0){
            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Akuntansi',
                'description' => 'Menghapus Data Diskon id = '. $id,
                'icon' => 'fas fa-dollar-sign',
                'ip_address' => $request->ip()
            ]);
            return redirect('/discount')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Diskon Sudah Dihapus!</div>');exit;
        } else {
            return redirect('/discount')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Diskon gagal Diubah!</div>');exit;
        }
    }
    private function resellerSelective($reseller)
    {
        if($reseller == 'a')
        {
            return json_decode(User::select('id')->where('roles', 2)->orWhere('roles', 3)->get(), true);
        } else if($reseller == 'b') {
            return json_decode(User::select('id')->where('roles', 2)->get(), true);
        } else if($reseller == 'c') {
            return json_decode(User::select('id')->where('roles', 3)->get(), true);
        } else {
            return [['id' => $reseller]];
        }
    }
}
