<?php

namespace App\Http\Controllers;

use App\accounting;
use App\Beban;
use App\Credit;
use App\Debit;
use App\Financials;
use App\Income;
use App\Materials;
use App\Orders;
use App\Purchase;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Cetak Laporan';
        $month = [ 1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $information = DB::table('information')->select('created_at')->where('id', 1)->first();
        $year = explode(' ', $information->created_at, -1);
        $year = explode('-', $year['0'], -2);$year = [ $year['0'], date('Y') ];
        return view('core/print', compact('title', 'month', 'year'));
    }
    public function ju(Request $request)
    {
        $title = 'Jurnal Umum';
        $ref = json_decode(accounting::select('ref')->addSelect('name')->get(), true);
        $finance = json_decode(Financials::select('financial.id')->addSelect('financial.description')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->addSelect('financial.created_at')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->orderBy('financial.created_at', 'asc')->get(), true);
        $total['debit'] = 0;
        $total['credit'] = 0;
        for($i=0;$i < count($finance);$i++)
        {
            $total['debit'] += $finance[$i]['debitdesc'];
            $total['credit'] += $finance[$i]['creditdesc'];
            $temp = explode(' ', $finance[$i]['created_at'], -1);
            $temp = explode('-', $temp['0']);
            $finance[$i]['created_at'] = $temp['2'];
            for($j=0;$j < count($ref);$j++)
            {
                if($ref[$j]['ref'] == $finance[$i]['refdebit']){
                    $finance[$i]['debit'] = $ref[$j]['name'];
                }
                if($ref[$j]['ref'] == $finance[$i]['refcredit']){
                    $finance[$i]['credit'] = $ref[$j]['name'];
                }
            }
        }
        $now = $this->getMonthName($request->s1) . ' '. $request->s2;
        $cetak = date('d') . ' '. $this->getMonthName(date('n')) . ' '. date('Y');
        $logo = public_path() . '/img/company/logo.png';
        $pdf = PDF::loadView('core/ju',compact('logo', 'title', 'cetak', 'finance', 'ref', 'total', 'now'));
        return $pdf->stream();
    }
    public function lr(Request $request)
    {
        $title = 'Laporan Laba Rugi';
        $ref = json_decode(accounting::select('ref')->addSelect('name')->get(), true);
        $finance = json_decode(Orders::select(DB::raw('SUM(total_price) as sales'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->where('status', '=', 6)->get(), true);
        $purchase = json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first(), true);
        $penjualan = Orders::select(DB::raw('SUM(total_price) as total'))->where('status', '=', 6)->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first();
        $pendapatan = Income::select(DB::raw('SUM(total) as total'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first();
        //Persediaan
        $persediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $y = ($request->s1 == 1 || $request->s1 == 01) ? [$request->s2-1, 12] : [$request->s2, $request->s1];
        $oldpersediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $y['0'])->whereMonth('financial.created_at', '=', $y['1'])->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $y['0'])->whereMonth('financial.created_at', '=', $y['1'])->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $persediaan = ['now' => (int)$persediaan['debit']['total'] - (int)$persediaan['credit']['total'], 'old' => (int)$oldpersediaan['debit']['total'] - (int)$oldpersediaan['credit']['total']];unset($y);unset($oldpersediaan);
        $hpp = (($persediaan['old'] + (int)$purchase['total']) - $persediaan['now']);

        $beban = json_decode(Beban::select('beban.total')->addSelect('accounting.name')->join('accounting', 'accounting.ref', 'beban.refbeban')->whereYear('beban.created_at', '=', $request->s2)->whereMonth('beban.created_at', '=', $request->s1)->get(), true);
        $totBeban = Beban::select(DB::raw('SUM(beban.total) as total'))->whereYear('beban.created_at', '=', $request->s2)->whereMonth('beban.created_at', '=', $request->s1)->first();
        $now = $this->getMonthName($request->s1) . ' '. $request->s2;
        $cetak = date('d') . ' '. $this->getMonthName(date('n')) . ' '. date('Y');
        $logo = public_path() . '/img/company/logo.png';
        $pdf = PDF::loadView('core/lr',compact('logo', 'title', 'beban', 'totBeban', 'cetak', 'finance', 'pendapatan', 'now', 'penjualan', 'purchase', 'ref', 'hpp'));
        return $pdf->stream();
    }
    public function pm(Request $request)
    {
        $title = 'Laporan Perubahan Modal';
        $ref = json_decode(accounting::select('ref')->addSelect('name')->get(), true);
        $finance = json_decode(Orders::select(DB::raw('SUM(total_price) as sales'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->where('status', '=', 6)->get(), true);
        $purchase = json_decode(Purchase::select(DB::raw('SUM(total) as total'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first(), true);
        $penjualan = Orders::select(DB::raw('SUM(total_price) as total'))->where('status', '=', 6)->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first();
        $pendapatan = Income::select(DB::raw('SUM(total) as total'))->whereYear('created_at', '=', $request->s2)->whereMonth('created_at', '=', $request->s1)->first();
        //Persediaan
        $persediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $y = ($request->s1 == 1 || $request->s1 == 01) ? [$request->s2-1, 12] : [$request->s2, $request->s1];
        $oldpersediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $y['0'])->whereMonth('financial.created_at', '=', $y['1'])->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $y['0'])->whereMonth('financial.created_at', '=', $y['1'])->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $persediaan = ['now' => (int)$persediaan['debit']['total'] - (int)$persediaan['credit']['total'], 'old' => (int)$oldpersediaan['debit']['total'] - (int)$oldpersediaan['credit']['total']];unset($y);unset($oldpersediaan);
        $hpp = (($persediaan['old'] + (int)$purchase['total']) - $persediaan['now']);

        $beban = json_decode(Beban::select('beban.total')->addSelect('accounting.name')->join('accounting', 'accounting.ref', 'beban.refbeban')->whereYear('beban.created_at', '=', $request->s2)->whereMonth('beban.created_at', '=', $request->s1)->get(), true);
        $totBeban = Beban::select(DB::raw('SUM(beban.total) as total'))->whereYear('beban.created_at', '=', $request->s2)->whereMonth('beban.created_at', '=', $request->s1)->first();
        $now = $this->getMonthName($request->s1) . ' '. $request->s2;
        $cetak = date('d') . ' '. $this->getMonthName(date('n')) . ' '. date('Y');
        $logo = public_path() . '/img/company/logo.png';
        $modal =['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('debit.refdebit', '=', 300)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('credit.refcredit', '=', 300)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $prive =['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('debit.refdebit', '=', 301)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', $request->s2)->whereMonth('financial.created_at', '=', $request->s1)->where('credit.refcredit', '=', 301)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $modal = $modal['credit']['total'] - $modal['debit']['total'];
        $prive = $prive['credit']['total'] - $prive['debit']['total'];
        $laba = (($penjualan->total + $pendapatan->total) - ($hpp + $totBeban->total));
        $pdf = PDF::loadView('core/pm',compact('logo', 'title', 'beban', 'totBeban', 'cetak', 'finance', 'pendapatan', 'now', 'penjualan', 'purchase', 'ref', 'hpp', 'modal', 'prive', 'laba'));
        return $pdf->stream();
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
