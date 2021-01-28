<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Debit;
use App\Discount;
use App\Financials;
use App\historyPeople;
use App\markets;
use App\OrderFinance;
use App\OrderHistory;
use App\Orders;
use App\ProductHPP;
use App\ProductOrder;
use App\products;
use Exception;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Pesanan';
        // $dateStart = explode(' ',Auth::user()->created_at, -1);
        // $dateStart = explode('-', $dateStart['0'], -1);
        // $option = [];
        // for($i=$dateStart['0'];$i<=date('Y'); $i++){$year = ($i == date('Y')) ? date('m') : 12;for($j=1;$j <= $year;$j++){$option[$i.$j] =  $this->getMonthName($j) . ' '.$i;}}
        // $option = [array_keys($option), array_values($option)];
        $orders = Orders::select('orders.id')->addSelect('markets.name')->addSelect('invoice')->addSelect('total_price')->addSelect('orders.created_at')->addSelect('status')->join('markets', 'markets.id', 'orders.id_markets')->join('users', 'users.id', 'orders.id_users')->whereYear('orders.created_at', '=', date('Y'))->whereMonth('orders.created_at', '=', date('m'))->where('users.id', Auth::user()->id)->get();
        $status = ['<div class="badge badge-danger">Dibatalkan</div>', '<div class="badge badge-primary">Dibuat</div>', '<div class="badge badge-success">Diambil</div>', '<div class="badge badge-success">Diantar</div>', '<div class="badge badge-success">Sampai</div>', '<div class="badge badge-success">Pembayaran</div>', '<div class="badge badge-success">Selesai</div>'];
        return view('core/cart', compact('title', 'orders', 'status'));
    }
    public function statusshow($id)
    {
        $title = 'Rincian Pesanan';
        $orders = Orders::select('orders.id')->addSelect('markets.name')->addSelect('orders.invoice')->addSelect('orders.total_price')->addSelect('orders.created_at')->addSelect('users.name as reseller')->addSelect('users.roles')->addSelect('orders.status')->addSelect('orders.purchase')->addSelect('orders.metode')->join('users', 'users.id', 'orders.id_users')->join('markets', 'markets.id', 'orders.id_markets')->where('orders.id', $id)->first();
        $temp = explode(' ',$orders['created_at'], -1);
        $temp = explode('-', $temp['0']);
        $time =$temp['2'].' '. $this->getMonthName($temp['1']) . ' '.$temp['0'];
        $product = json_decode(ProductOrder::select('product_orders.id')->addSelect('product_orders.quantity')->addSelect('product_orders.price')->addSelect(DB::raw('(product_orders.price*product_orders.quantity) - (product_orders.discount*product_orders.quantity) as total'))->addSelect('product_orders.discount')->addSelect('products.title')->join('products', 'products.id', 'product_orders.product_id')->where('order_id', $id)->get(), true);
        $total = 0;
        for($i=0;$i<count($product);$i++)
        {
            $total += $product[$i]['total'];
        }
        return view('core/status', compact('title', 'orders', 'time', 'product', 'total'));
    }
    public function orderReturn($id)
    {
        $title = 'Return Pesanan';
        $orders = Orders::select('orders.id')->addSelect('markets.name')->addSelect('orders.invoice')->addSelect('orders.total_price')->addSelect('orders.created_at')->addSelect('users.name as reseller')->addSelect('users.roles')->addSelect('orders.status')->addSelect('orders.purchase')->addSelect('orders.metode')->join('users', 'users.id', 'orders.id_users')->join('markets', 'markets.id', 'orders.id_markets')->whereYear('orders.created_at', '=', date('Y'))->whereMonth('orders.created_at', '=', date('m'))->where('orders.id', $id)->first();
        $temp = explode(' ',$orders->created_at, -1);
        $temp = explode('-', $temp['0']);
        $time =$temp['2'].' '. $this->getMonthName($temp['1']) . ' '.$temp['0'];
        $product = json_decode(ProductOrder::select('product_orders.id')->addSelect('product_orders.quantity')->addSelect('product_orders.price')->addSelect(DB::raw('(product_orders.price*product_orders.quantity) - (product_orders.discount*product_orders.quantity) as total'))->addSelect('product_orders.discount')->addSelect('products.title')->join('products', 'products.id', 'product_orders.product_id')->where('order_id', $id)->get(), true);
        $total = 0;
        for($i=0;$i<count($product);$i++)
        {
            $total += $product[$i]['total'];
        }
        return view('core/return', compact('title', 'orders', 'time', 'product', 'total'));
    }
    public function postReturn(Request $request, $id)
    {
        if((count($request->r1) > 0) &&(count($request->r2) > 0) &&(count($request->r3) > 0))
        {
            // id => product_order
            $order = Orders::select('total_price')->where('id', $id)->first();
            $data = json_decode(ProductOrder::select('product_orders.id')->addSelect('product_orders.product_id')->addSelect('product_orders.quantity')->addSelect('product_hpp.hpp')->addSelect('product_orders.price')->addSelect('product_orders.discount')->addSelect('products.stock')->join('products', 'products.id', 'product_orders.product_id')->join('product_hpp', 'product_orders.product_id', 'product_hpp.id_product')->get(), true);$total = 0;$hpp = 0;
            $mD = json_decode(OrderFinance::select('id_financial')->addSelect('debit.id as id_debit')->addSelect('debit.debitdesc')->addSelect('debit.refdebit')->addSelect('credit.id as id_credit')->addSelect('credit.creditdesc')->addSelect('credit.refcredit')->join('financial', 'financial.id', 'order_finance.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('id_order', $id)->get(), true);
            for($i=0;$i<count($request->r1);$i++)
            {
                for($j=0;$j < count($data);$j++)
                {
                    if($request->r0[$i] == $data[$j]['id'])
                    {
                        $total += (int)$request->r2[$i] * ((int)$data[$j]['price'] - (int)$data[$j]['discount']);
                        $hpp += (int)$request->r2[$i] * (int)$data[$j]['hpp'];
                        if($request->r3[$i] == 1)
                        {
                            products::where('id', $data[$j]['product_id'])->update(['stock' => ($data[$j]['stock'] + $request->r2[$i])]);
                            ProductOrder::where('id', $request->r0[$i])->update(['quantity' => ($data[$j]['quantity'] - $request->r2[$i])]);
                        }
                    }
                }
            }
            $affected = Orders::where('id', $id)->update(['total_price' => ($order->total_price - $total)]);
            Financials::where('id', $mD['0']['id_financial'])->update(['updated_by' => Auth::user()->id]);
            //Kas // Penjualan
            Debit::where('id', $mD['0']['id_debit'])->update(['refdebit' => $mD['0']['refcredit'], 'debitdesc' => ($mD['0']['debitdesc'] - $total)]);
            Credit::where('id', $mD['0']['id_credit'])->update(['refcredit' => $mD['0']['refdebit'], 'creditdesc' => ($mD['0']['creditdesc'] - $total)]);
            //BPP // Pers. Brg Dgng
            Debit::where('id', $mD['1']['id_debit'])->update(['refdebit' => $mD['1']['refcredit'], 'debitdesc' => ($mD['0']['debitdesc'] - $hpp)]);
            Credit::where('id', $mD['1']['id_credit'])->update(['refcredit' => $mD['1']['refdebit'], 'creditdesc' => ($mD['0']['creditdesc'] - $hpp)]);
            if($affected > 0)
            {
                OrderHistory::create([
                    'id_order' => $id,
                    'status' => 7,
                    'description' => 'Order Direturn & Diterima Oleh ' . Auth::user()->name
                ]);
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Pesanan',
                    'description' => 'Mereturn Order id = '. $id,
                    'icon' => 'fas fa-shopping-cart',
                    'ip_address' => $request->ip()
                ]);
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Telah Direturn!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Gagal Direturn!</div>');exit;
            }
        }
    }
    public function statusdestroy(Request $request, $id)
    {
        $status = Orders::select('status')->where('id', $id)->first();
        if($status->status != 0 || $status->status != 6){
            $financial = json_decode(OrderFinance::select('id_financial')->where('id_order', $id)->get(), true);
            for($i=0;$i < count($financial); $i++)
            {
                Financials::where('id', $financial[$i]['id_financial'])->update(['deleted_by' => Auth::user()->id]);
                Financials::where('id', $financial[$i]['id_financial'])->delete();
            }
            $product = ProductOrder::select('product_id')->addSelect('quantity')->where('order_id', '=', $id)->get();
            for($i=0;$i<count($product);$i++)
            {
                $stockNow = products::select('stock')->where('id', $product[$i]['product_id'])->first();
                $temp = $stockNow->stock + $product[$i]['quantity'];
                products::where('id', $product[$i]['product_id'])->update(['stock' => $temp]);
            }
            $affected = Orders::where('id', $id)->update(['status' => 0]);
            if($affected > 0)
            {
                OrderHistory::create([
                    'id_order' => $id,
                    'status' => 0,
                    'description' => 'Order Dibatalkan Oleh ' . Auth::user()->name
                ]);
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Pesanan',
                    'description' => 'Membatalkan Order id = '. $id,
                    'icon' => 'fas fa-shopping-cart',
                    'ip_address' => $request->ip()
                ]);
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Telah Dibatalkan!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Gagal Dibatalkan!</div>');exit;
            }
        } else {
            if($status->status == 0){
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Sebelumnya sudah Dibatalkan!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Sudah Selesai dan Tidak bisa Dibatalkan!</div>');exit;
            }
        }
    }
    public function cetak($id)
    {
        $title = 'Cetak Nota';
        $orders = Orders::select('orders.id')->addSelect('markets.name as MarketName')->addSelect('markets.owner as MarketOwner')->addSelect('markets.address as MarketAddress')->addSelect('orders.invoice')->addSelect('orders.total_price')->addSelect('orders.created_at')->addSelect('users.name as reseller')->addSelect('users.roles')->addSelect('markets.id as IDPEL')->addSelect('orders.status')->addSelect('orders.purchase')->addSelect('orders.metode')->join('users', 'users.id', 'orders.id_users')->join('markets', 'markets.id', 'orders.id_markets')->where('orders.id', $id)->first();
        $temp = explode(' ',$orders->created_at, -1); $temp = explode('-', $temp['0']); $time =$temp['2'].' '. $this->getMonthName($temp['1']) . ' '.$temp['0'];
        $product = json_decode(ProductOrder::select('product_orders.id')->addSelect('product_orders.quantity')->addSelect('product_orders.price')->addSelect(DB::raw('(product_orders.price*product_orders.quantity) - (product_orders.discount*product_orders.quantity) as total'))->addSelect('product_orders.discount')->addSelect('products.title')->join('products', 'products.id', 'product_orders.product_id')->where('order_id', $id)->get(), true);
        $total = 0;
        for($i=0;$i<count($product);$i++)
        {
            $total += $product[$i]['total'];
        }
        $logo = public_path() . '/img/company/logo.png';
        $pdf = PDF::loadView('core/nota',['logo' => $logo, 'title' => $title, 'orders' => $orders, 'time' => $time, 'product' => $product, 'total' => $total]);
        return $pdf->stream();
    }
    public function product(Request $request, $id)
    {
        $orders = Orders::select('status')->where('id', $id)->first();
        if($orders->status == 1){
            $affected = Orders::where('id', $id)->update(['status' => 2]);
            if($affected > 0)
            {
                OrderHistory::create([
                    'id_order' => $id,
                    'status' => 2,
                    'description' => 'Produk Order Sudah Disiapkan Oleh ' . Auth::user()->name
                ]);
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Pesanan',
                    'description' => 'Menyiapkan Produk Order id = '. $id,
                    'icon' => 'fas fa-shopping-cart',
                    'ip_address' => $request->ip()
                ]);
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Status Telah Diubah!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
            }
        } else {
            return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
        }
    }
    public function bring(Request $request, $id)
    {
        $orders = Orders::select('status')->addSelect('users.name')->join('users', 'users.id', 'orders.id_users')->where('orders.id', $id)->first();
        if($orders->status == 2){
            $affected = Orders::where('id', $id)->update(['status' => 3]);
            if($affected > 0)
            {
                OrderHistory::create([
                    'id_order' => $id,
                    'status' => 3,
                    'description' => 'Produk Order Sudah Dibawa Oleh ' . $orders->name
                ]);
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Pesanan',
                    'description' => 'Menyetujui Untuk menyerahkan Produk dan dibawa oleh reseller Order id = '. $id,
                    'icon' => 'fas fa-shopping-cart',
                    'ip_address' => $request->ip()
                ]);
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Status Telah Diubah!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
            }
        } else {
            return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
        }
    }
    public function to(Request $request, $id)
    {
        $orders = Orders::select('status')->addSelect('users.name')->join('users', 'users.id', 'orders.id_users')->where('orders.id', $id)->first();
        if($orders->status == 3){
            $affected = Orders::where('id', $id)->update(['status' => 4]);
            if($affected > 0)
            {
                OrderHistory::create([
                    'id_order' => $id,
                    'status' => 4,
                    'description' => 'Produk Order Sudah Sampai DiTujuan'
                ]);
                historyPeople::create([
                    'id_users' => Auth::user()->id,
                    'title' => 'Pesanan',
                    'description' => 'Produk Order Sudah Sampai Di tujuan Order id = '. $id,
                    'icon' => 'fas fa-shopping-cart',
                    'ip_address' => $request->ip()
                ]);
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Status Telah Diubah!</div>');exit;
            } else {
                return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
            }
        } else {
            return redirect('/cart/status/'.$id)->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Order Status Gagal diubah!</div>');exit;
        }
    }
    public function orderStatus($id)
    {
        $title = 'Status Order';
        $history = json_decode(OrderHistory::select('status')->addSelect('description')->addSelect('created_at')->where('id_order', $id)->get(), true);
        for($i=0;$i<count($history);$i++)
        {
            $time = explode(' ', $history[$i]['created_at']);$temp = explode('-', $time['0']);
            $history[$i]['created_at'] = $time['1'] . ' ' . $temp['2'] . ' '. $this->getMonthName($temp['1']) . ' '. $temp['0'];
        } unset($time);unset($temp);
        $status = ['<div class="badge badge-danger">Dibatalkan</div>', '<div class="badge badge-primary">Dibuat</div>', '<div class="badge badge-success">Diambil</div>', '<div class="badge badge-success">Diantar</div>', '<div class="badge badge-success">Sampai</div>', '<div class="badge badge-success">Pembayaran</div>', '<div class="badge badge-success">Selesai</div>', '<div class="badge badge-danger">Return</div>'];
        return view('core/orderStatus', compact('title', 'history', 'status', 'id'));
    }
    public function create()
    {
        $title = 'Buat Pesanan';
        $products = json_decode(products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('price')->addSelect('stock')->get(), true);
        return view('core/createCart', compact('title', 'products'));
    }

    public function show(Request $request)
    {
        if(!is_null($request->session('cart'))){
            $request->session()->forget('cart');
        } else {
            return redirect(url('/cart'));exit;
        }

        $products = json_decode(products::select('id')->addSelect('title')->addSelect('description')->addSelect('cover')->addSelect('price')->addSelect('stock')->get(), true);

        $cart = [];
        if(count($products) > 0){
            $tot = 0;
            for($i = 0; $i < count($products); $i++ ){
                if(!is_null($request->product['p'. $products[$i]['id']])){
                    $discount = Discount::select('sale')->where('id_user', Auth::user()->id)->where('id_product', $products[$i]['id'])->first();
                    $discount = (!empty($discount)) ? $discount->sale : 0;
                    $total = (((int)$products[$i]['price']) * ((int)$request->product['p'. $products[$i]['id']]) - ($discount * ((int)$request->product['p'. $products[$i]['id']])));
                    $tot += $total;
                    $readyStock = ($products[$i]['stock'] - ((int)$request->product['p'. $products[$i]['id']]));
                    if($readyStock < 0){ return redirect(url('/cart/create'))->with('status', '<div class="alert alert-danger" role="alert"><strong>Stok Kelebihan</strong>, Pemesanan Melebihi Stok yang sudah disediakan!</div>') ;exit; }
                    $cart[] = [
                        'id' => $products[$i]['id'],
                        'item' => $products[$i]['title'],
                        'qty' => $request->product['p'.$products[$i]['id']],
                        'price' => $products[$i]['price'],
                        'discount' => $discount,
                        'total' => $total
                    ];
                }
            }
            if(empty($cart)){ return redirect(url('/cart/create'))->with('status', '<div class="alert alert-danger" role="alert"><strong>Kosong</strong>, Tidak Ada Produk Yang dipesan!</div>') ;exit; }
            $request->session()->flash('cart', $cart);

            $stores = markets::select('markets.id')->addSelect('markets.name')->addSelect('markets.owner')->join('reseller_market', 'reseller_market.id_market', 'markets.id')->where('reseller_market.id_reseller', Auth::user()->id)->get();
            $title = 'Buat Pesanan';
            $invoice = ($this->INV([5, 3])); // 1 for Abjad 2 for Number
        }
        return view('core/invoiceCart', compact('title', 'stores', 'invoice', 'products', 'cart', 'tot'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [ 'invoice' => 'required', 'billTo' => 'required', 'purchase' => 'required', 'method' => 'required']);
        $cart = [];
        if(!is_null($request->session('cart'))) { $cart = $request->session()->get('cart');$request->session()->forget('cart'); }
        if(empty($cart)){ return redirect(url('/cart'));exit; }
        $ref = ($request->purchase == 1) ? [ 101, 400, 614, 102 ] : [ 103, 400, 614, 102 ];$total = 0;$hpp = 0;
        for($i=0; $i < count($cart); $i++){ $total += $cart[$i]['total']; $temp = ProductHPP::select('hpp')->where('id_product', $cart[$i]['id'])->first(); $hpp += ($temp->hpp*$cart[$i]['qty']);}
        $orders = new Orders();
        $orders->id_users = Auth::user()->id;
        $orders->id_markets = $request->billTo;
        $orders->purchase = $request->purchase;
        $orders->total_price = $total;
        $orders->metode = $request->method;
        $orders->invoice = $request->invoice;
        $orders->status = 1;
        $orders->save();

        $debit = new Debit();
        $debit->refdebit = $ref['0'];
        $debit->debitdesc = $total;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $ref['1'];
        $credit->creditdesc = $total;
        $credit->save();

        $financial = new Financials();
        $financial->id_debit = $debit->id;
        $financial->id_credit = $credit->id;
        $financial->description = 'Membuat Pesanan';
        $financial->created_by = Auth::user()->id;
        $financial->save();

        OrderFinance::create([
            'id_order' => $orders->id,
            'id_financial' => $financial->id
        ]);

        $debit = new Debit();
        $debit->refdebit = $ref['2'];
        $debit->debitdesc = $hpp;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $ref['3'];
        $credit->creditdesc = $hpp;
        $credit->save();

        $financial = new Financials();
        $financial->id_debit = $debit->id;
        $financial->id_credit = $credit->id;
        $financial->description = 'Membuat Pesanan';
        $financial->created_by = Auth::user()->id;
        $financial->save();

        OrderFinance::create([
            'id_order' => $orders->id,
            'id_financial' => $financial->id
        ]);

        OrderHistory::create([
            'id_order' => $orders->id,
            'status' => 1,
            'description' => 'Order Dibuat Oleh '. Auth::user()->name
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Pesanan',
            'description' => 'Membuat Order id = '. $orders->id,
            'icon' => 'fas fa-shopping-cart',
            'ip_address' => $request->ip()
        ]);
        for($i=0;$i < count($cart);$i++)
        {
            $keranjang = [
                'order_id' => $orders->id, 'product_id' => $cart[$i]['id'], 'quantity' =>$cart[$i]['qty'], 'price' =>$cart[$i]['price'], 'discount' =>$cart[$i]['discount'],
            ];
            ProductOrder::create($keranjang);
            $stockNow = products::select('stock')->where('id', $cart[$i]['id'])->first();
            $stockNow = $stockNow->stock - $cart[$i]['qty'];
            products::where('id', $cart[$i]['id'])->update(['stock' => $stockNow]);
        }
        return redirect('/cart/status/'.$orders->id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Pemesananmu Sudah Dibuat!</div>');exit;
    }
    public function doneCart($id)
    {
        $orders = OrderFinance::select('order_finance.id_order as id')->addSelect('orders.purchase')->join('orders', 'orders.id', 'order_finance.id_order')->where('orders.id', $id)->first();
        $finance = json_decode(OrderFinance::select('debit.refdebit')->addSelect('financial.id_debit')->join('financial', 'financial.id', 'order_finance.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->where('id_order', $id)->get(), true);
        if($orders->purchase == 2) {
            for($i=0;$i<count($finance);$i++)
            {
                if($finance[$i]['refdebit'] == 103){
                    Debit::where('id', $finance[$i]['id_debit'])->update(['refdebit' => 101]);
                }
            }
        }
        Orders::where('id', $id)->update([
            'status' => 6
        ]);
        OrderHistory::create([
            'id_order' => $orders->id,
            'status' => 5,
            'description' => 'Pembayaran diverifikasi oleh '. Auth::user()->name
        ]);
        OrderHistory::create([
            'id_order' => $orders->id,
            'status' => 6,
            'description' => 'Order Selesai dan diverif oleh '. Auth::user()->name
        ]);
        return redirect('/cart/status/'.$orders->id)->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Order Telah Selesai!</div>');exit;
    }
    public function pesananindex(Request $request)
    {
        $orders = [];
        if(!is_null($request->reseller) && !is_null($request->market)&&!is_null($request->time)&&!is_null($request->status))
        {
            $time = $this->getTime($request->time);
            $market = ($request->market == 'a') ? ['>', 0] : ['=', $request->market];
            $status = ($request->status == 'a') ? ['>=', 0] : ['=', $request->status];
            $reseller = $this->manageReseller($request->reseller);
            $orders = Orders::select('orders.id')->addSelect('markets.name as market')->addSelect('users.name as reseller')->addSelect('invoice')->addSelect('total_price')->addSelect('orders.created_at')->addSelect('status')->join('markets', 'markets.id', 'orders.id_markets')->join('users', 'users.id', 'orders.id_users')->where('markets.id', $market['0'], $market['1'])->where($reseller['2'], $reseller['0'], $reseller['1'])->where('status', $status['0'], $status['1'])->get();
        }
        $title = 'Pesanan';
        $dateStart = DB::table('information')->get('created_at')->first();$dateStart = explode(' ', $dateStart->created_at, -1);$dateStart = explode('-', $dateStart['0'], -1);$option = [];
        for($i=$dateStart['0'];$i<=date('Y'); $i++){$year = ($i == date('Y')) ? date('m') : 12;for($j=1;$j <= $year;$j++){$option[$i.$j] =  $this->getMonthName($j) . ' '.$i;}}unset($dateStart);
        $option = [array_keys($option), array_values($option)];
        $resellers = User::select('id')->addSelect('name')->addSelect('roles')->where('roles', 2)->orWhere('roles', 3)->where('is_active', 1)->get();
        $markets = markets::select('id')->addSelect('name')->where('is_active', 1)->get();
        $status = ['<div class="badge badge-danger">Dibatalkan</div>', '<div class="badge badge-primary">Dibuat</div>', '<div class="badge badge-success">Diambil</div>', '<div class="badge badge-success">Diantar</div>', '<div class="badge badge-success">Sampai</div>', '<div class="badge badge-success">Pembayaran</div>', '<div class="badge badge-success">Selesai</div>'];
        $stats = ['Order Dibatalkan', 'Order Dibuat', 'Produk Diambil', 'Produk Diantar', 'Produk Sampai', 'Pembayaran Order', 'Order Selesai'];
        return view('core/pesanan', compact('title', 'orders', 'stats', 'status', 'option', 'markets', 'resellers'));
    }
    private function manageReseller($user)
    {
        if($user == 'a') {
            return ['>=', 2, 'users.roles'];
        } else if($user == 'b') {
            return ['=', 2, 'users.roles'];
        } else if($user == 'c') {
            return ['=', 3, 'users.roles'];
        } else {
            return ['=', (int)$user, 'users.id'];
        }
    }
    private function getTime($time)
    {
        if(strlen($time) == 6){
            $time = (str_split($time));
            return [$time['0'].$time['1'].$time['2'].$time['3'], $time['4'].$time['5']];
        } else if(strlen($time) == 5){
            $time = (str_split($time));
            return [$time['0'].$time['1'].$time['2'].$time['3'], $time['4']];
        } else {
            return [date('Y'), date('m')];
        }
    }
    private function INV($length)
    {
        try{
            $re = explode(' ', 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z 1 2 3 4 5 6 7 8 9 0');
            $y = str_split(date('Y'), 2);
            $inv = 'SPR'.$y['1'];
            for($i=0;$i < ($length['0']); $i++){
                $inv .= $re[rand(0, 25)];
            }
            for($i=0;$i<$length['1'];$i++){
                $inv .= $re[rand(26, 35)];
            }
            $inv .= 'INV';
            return ($inv);exit;
        } catch(Exception $e){
            return ['type' => 'error', 'code' => 'Kesalahan: '.$e];exit;
        }
    }
    private function getMonthName($m)
    {
        if($m == '1' || $m == '01')
        {
            return 'Januari';
        }
        else if($m == '2' || $m == '02')
        {
            return 'Februari';
        }
        else if($m == '3' || $m == '03')
        {
            return 'Maret';
        }
        else if($m == '4' || $m == '04')
        {
            return 'April';
        }
        else if($m == '5' || $m == '05')
        {
            return 'Mei';
        }
        else if($m == '6' || $m == '06')
        {
            return 'Juni';
        }
        else if($m == '7' || $m == '07')
        {
            return 'Juli';
        }
        else if($m == '8' || $m == '08')
        {
            return 'Agustus';
        }
        else if($m == '9' || $m == '09')
        {
            return 'September';
        }
        else if($m == '10')
        {
            return 'Oktober';
        }
        else if($m == '11')
        {
            return 'November';
        }
        else if($m == '12')
        {
            return 'Desember';
        }
    }
}
