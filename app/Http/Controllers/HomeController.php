<?php

namespace App\Http\Controllers;

use App\accounting;
use App\Credit;
use App\Debit;
use App\Financials;
use App\historyPeople;
use App\Income;
use App\Orders;
use App\Outcome;
use App\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);
    }
    public function index()
    {
        $title = 'Home';
        $order = ['cancel' => Orders::select('id')->where('status', 0)->count(), 'process' => Orders::select('id')->where('status', '>', 0)->where('status', '<', 6)->count(), 'complete' => Orders::select('id')->where('status', 6)->count()];
        $sales = Orders::select(DB::raw('SUM(orders.total_price) as totalSales'))->where('status', '>', 0)->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->first();
        $weekOrder = Orders::select(DB::raw('sum(total_price) as total'))->whereDate('created_at', '>=', Carbon::today()->subDays(6))->first();
        $balance = accounting::select('description')->where('ref', 101)->first();
        return view('core/home', compact('title', 'order', 'sales', 'weekOrder', 'balance'));
    }
    public function search(Request $request)
    {
        $title = 'Pencarian';$data = ['q' => $request->q];
        return view('core/search', compact('title', 'data'));
    }
    public function reset()
    {
        return redirect('/home')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Reset Belum Bisa dilakukan!</div>');exit;
    }
    public function Graph()
    {
        try{
            $weekOrder = Orders::select(DB::raw('sum(total_price) as total'))->whereDate('created_at', '>=', Carbon::today()->subDays(6))->first();
            $data = [
                'sales' => [
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(6))->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(5))->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(4))->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(3))->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(2))->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::yesterday())->first(),true),
                    json_decode(Orders::select(DB::raw('SUM(total_price) as total'))->whereDate('created_at', '=', Carbon::today())->first(),true),
                ], 'material' => [
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(6))->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(5))->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(4))->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(3))->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(2))->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::yesterday())->first(),true),
                    json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today())->first(),true),
                ], 'income' => [
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(6))->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(5))->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(4))->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(3))->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(2))->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::yesterday())->first(),true),
                    json_decode(Income::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today())->first(),true),
                ], 'outcome' => [
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(6))->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(5))->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(4))->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(3))->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today()->subDays(2))->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::yesterday())->first(),true),
                    json_decode(Outcome::select(DB::raw('SUM(total) as total'))->whereDate('created_at', '=', Carbon::today())->first(),true),
                ], 'day' => [
                    Carbon::now()->subDays(6)->toDateString(),
                    Carbon::now()->subDays(5)->toDateString(),
                    Carbon::now()->subDays(4)->toDateString(),
                    Carbon::now()->subDays(3)->toDateString(),
                    Carbon::now()->subDays(2)->toDateString(),
                    Carbon::yesterday()->toDateString(),
                    Carbon::now()->toDateString(),
                ], 'ordersWeek' => [
                    $weekOrder
                ]
            ];
            return json_encode(['error' => false, 'message' => 'your request is completed', 'data' => $data]);exit;
        } catch(Exception $e){
            echo json_encode(['error' => true, 'message' => 'Error: '.$e, 'data' => null]);exit;
        }
    }
    public function activities()
    {
        $title = 'Aktivitas';
        $activities = historyPeople::select('title', 'description', 'icon', 'ip_address', 'created_at')->where('id_users', Auth::user()->id)->get();
        return view('core/activities', compact('title', 'activities'));
    }
    public function profile()
    {
        $title = 'Profil';
        $data = User::select('id')->addSelect('name')->addSelect('religion')->addSelect('gender')->addSelect('image')->addSelect('phone')->addSelect('address')->addSelect('email')->addSelect('username')->where('id', Auth::user()->id)->first();
        return view('core/profile', compact('title', 'data'));
    }
    public function ChangeProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:128',
            'religion' => 'required|max:1',
            'gender' => 'required|max:1',
            'phone' => 'required|min:8',
            'address' => 'required',
            'email' => 'required|email',
            'username' => 'required',
        ]);
        $oldImage = User::select('image')->addSelect('password')->where('ID', Auth::user()->id)->first();
        $name = $oldImage->image;
        if(!is_null($request->file('image'))){
                if (!is_null($oldImage)) {
                    if($name !== 'nophoto.png'){
                        File::delete(public_path() . '/img/profil/' . $oldImage->image);
                    }
                }
                $file = $request->file('image');
                $name = $this->imgRandom($file->getClientOriginalName());
                $file->move(public_path() . '/img/profil/', $name);
        }
        $affected = User::where('id', Auth::user()->id)->update([
            'name' => $request->name,
            'religion' => $request->religion,
            'gender' => $request->gender,
            'image' => $name,
            'password' => $oldImage->password,
            'phone' => $request->phone,
            'address' =>  $request->address,
            'email' =>  $request->email,
            'username' =>  $request->username,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Profil',
            'description' => 'mengubah Profil',
            'icon' => 'fas fa-user',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0){
            return redirect('/profile')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Profilmu Sudah diubah!</div>');exit;
        } else {
            return redirect('/profile')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Profilmu gagal diubah! periksa kembali form</div>');exit;
        }
    }
    public function license()
    {
        $title = 'Lisensi';
        return view('core/license', compact('title'));
    }
    public function setting()
    {
        $title = 'Pengaturan';
        return view('core/setting', compact('title'));
    }
    private function imgRandom($img)
    {
        $img = (explode('.', $img));
        $ekstensi = $img[count($img) - 1];
        unset($img[count($img) - 1]);
        $img = implode('.', $img) . '.' . time() . '.' . $ekstensi;
        return $img;
    }
    private function getDayName($d)
    {
        if (intval($d) == 1){
            return 'Senin';
        } else if(intval($d) == 2) {
            return 'Selasa';
        } else if(intval($d) == 3) {
            return 'Rabu';
        } else if(intval($d) == 4) {
            return 'Kamis';
        } else if(intval($d) == 5) {
            return 'Jumat';
        } else if(intval($d) == 6) {
            return 'Sabtu';
        } else if(intval($d) == 7) {
            return 'Minggu';
        }
    }
    private function getMonthName($month)
    {
        if (intVal($month) == 1) {
            return 'January';
        } else if (intVal($month) == 2) {
            return 'February';
        } else if (intVal($month) == 3) {
            return 'March';
        } else if (intVal($month) == 4) {
            return 'April';
        } else if (intVal($month) == 5) {
            return 'May';
        } else if (intVal($month) == 6) {
            return 'Juny';
        } else if (intVal($month) == 7) {
            return 'July';
        } else if (intVal($month) == 8) {
            return 'August';
        } else if (intVal($month) == 9) {
            return 'September';
        } else if (intVal($month) == 10) {
            return 'October';
        } else if (intVal($month) == 11) {
            return 'November';
        } else if (intVal($month) == 12) {
            return 'December';
        }
    }
}
