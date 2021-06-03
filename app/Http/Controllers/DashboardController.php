<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Transaksi;
use App\User;

class DashboardController extends Controller
{
  public $bulan = [
    ["Januari", 31],
    ["Februari", 28],
    ["Maret", 31],
    ["April", 30],
    ["Mei", 31],
    ["Juni", 30],
    ["Juli", 31],
    ["Agustus", 31],
    ["September", 30],
    ["Oktober", 31],
    ["November", 30],
    ["Desember", 31],
  ];

  public $colors = [
    'success' => '#28a745',
    'warning' => '#ffc107',
    'danger' => '#dc3545',
    'info' => '#17a2b8',
    'teal' => '#20c997',
  ];

  /**
   * Menampilkan dashboard
   * 
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
	  // Count data member
	  $member = User::where('role','=',role_member())->count();

	  // Count data top up
	  $top_up = Transaksi::join('users','transaksi.id_user','=','users.id_user')->where('role','=',role_member())->where('jenis_transaksi','=',1)->sum('nominal_transaksi');

		// Count data TRX
	  $trx = Transaksi::join('users','transaksi.id_user','=','users.id_user')->where('role','=',role_member())->where('jenis_transaksi','=',2)->sum('nominal_transaksi');

	  // Count data saldo
	  $saldo = $top_up - $trx;

	  return view('admin/dashboard/index', [
		  'bulan' => $this->bulan,
		  'member' => $member,
		  'top_up' => $top_up,
		  'trx' => $trx,
		  'saldo' => $saldo,
	  ]);
  }
	
  /**
   * Data grafik transaksi
   * 
   * @return \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
  public function graph_transaksi(Request $request)
  {
    // Get bulan
    $bulan = $this->bulan[$request->bulan-1];

    $labels = [];
    $data_transaksi_1 = [];
    $data_transaksi_2 = [];
    $bulan[1] = $request->bulan == 2 && $request->tahun % 4 == 0 ? $bulan[1]+1 : $bulan[1];
    for($i = 1; $i <= $bulan[1]; $i++){
      // Get transaksi top up
      $transaksi_1 = Transaksi::where('jenis_transaksi','=',1)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->sum('nominal_transaksi');

      // Get transaksi TRX
      $transaksi_2 = Transaksi::where('jenis_transaksi','=',2)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->sum('nominal_transaksi');

      // Push data
      array_push($labels, $i);
      array_push($data_transaksi_1, $transaksi_1);
      array_push($data_transaksi_2, $transaksi_2);
    }

    $datasets = [
      [
        'label' => 'Top Up',
        'data' => $data_transaksi_1,
        'backgroundColor' => $this->colors['success'],
        'borderColor' => $this->colors['success'],
        'fill' => false,
        'borderWidth' => 1,
      ],
      [
        'label' => 'TRX',
        'data' => $data_transaksi_2,
        'backgroundColor' => $this->colors['danger'],
        'borderColor' => $this->colors['danger'],
        'fill' => false,
        'borderWidth' => 1,
      ]
    ];

    $data['labels'] = $labels;
    $data['datasets'] = $datasets;
    $data['bulan'] = $bulan[0];
    $data['tahun'] = $request->tahun;

    echo json_encode($data);
  }

  /**
   * Data grafik kunjungan
   * 
   * @return \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
  public function graph_kunjungan(Request $request)
  {
    // Get bulan
    $bulan = $this->bulan[$request->bulan-1];

    $labels = [];
    $data_kunjungan = [];
    $bulan[1] = $request->bulan == 2 && $request->tahun % 4 == 0 ? $bulan[1]+1 : $bulan[1];
    for($i = 1; $i <= $bulan[1]; $i++){
      // Get kunjungan
      $kunjungan = Transaksi::whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->count();

      // Push data
      array_push($labels, $i);
      array_push($data_kunjungan, $kunjungan);
    }

    $datasets = [
      [
        'label' => 'Kunjungan',
        'data' => $data_kunjungan,
        'backgroundColor' => $this->colors['info'],
        'borderColor' => $this->colors['info'],
        'fill' => false,
        'borderWidth' => 1,
      ]
    ];

    $data['labels'] = $labels;
    $data['datasets'] = $datasets;
    $data['bulan'] = $bulan[0];
    $data['tahun'] = $request->tahun;

    echo json_encode($data);
  }
}
