<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Transaksi;
use App\User;

class ReportController extends Controller
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
     * Menampilkan analisa
     * 
     * @return \Illuminate\Http\Response
     */
    public function analisa()
    {
        return view('admin/report/analisa', [
            'bulan' => $this->bulan,
        ]);
    }

    /**
     * Data grafik usia
     * 
     * @return \Illuminate\Http\Response
     */
    public function graph_usia()
    {
        // Count user usia di bawah 20 tahun
        $user_under_20 = User::where('role','=',role_member())->whereYear('tanggal_lahir','>',(date('Y')-20))->count();

        // Count user usia di antara 20 tahun - 30 tahun
        $user_between_20_30 = User::where('role','=',role_member())->whereYear('tanggal_lahir','<=',(date('Y')-20))->whereYear('tanggal_lahir','>=',(date('Y')-30))->count();

        // Count user usia di antara 31 tahun - 40 tahun
        $user_between_31_40 = User::where('role','=',role_member())->whereYear('tanggal_lahir','<=',(date('Y')-31))->whereYear('tanggal_lahir','>',(date('Y')-40))->count();

        // Count user usia di atas 40 tahun
        $user_above_40 = User::where('role','=',role_member())->whereYear('tanggal_lahir','<=',(date('Y')-40))->count();

        // Push data
        $data['data'] = [
            $user_under_20,
            $user_between_20_30,
            $user_between_31_40,
            $user_above_40,
        ];

        // Push label
        $data['label'] = ['<20', '20-30', '31-40', '>40'];

        // Push background color
        $data['bgcolor'] = [
            $this->colors['info'],
            $this->colors['warning'],
            $this->colors['success'],
            $this->colors['danger'],
        ];

        echo json_encode($data);
    }

    /**
     * Data grafik jenis kelamin
     * 
     * @return \Illuminate\Http\Response
     */
    public function graph_gender()
    {
        // Count user gender laki-laki
        $user_laki = User::where('role','=',role_member())->where('jenis_kelamin','=','L')->count();

        // Count user gender perempuan
        $user_perempuan = User::where('role','=',role_member())->where('jenis_kelamin','=','P')->count();

        // Push data
        $data['data'] = [
            $user_laki,
            $user_perempuan,
        ];

        // Push label
        $data['label'] = ['Laki-Laki', 'Perempuan'];

        // Push background color
        $data['bgcolor'] = [
            $this->colors['danger'],
            $this->colors['warning'],
        ];

        echo json_encode($data);
    }

    /**
     * Data grafik frekuensi kedatangan / transaksi
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function graph_kedatangan(Request $request)
    {
        if($request->query('t1') == null && $request->query('t2') == null){
            // Count transaksi antara pukul 0 sampai 6
            $kedatangan_between_0_6 = Transaksi::whereTime('waktu_transaksi','>=','00:00:00')->whereTime('waktu_transaksi','<=','06:00:00')->count();

            // Count transaksi antara pukul 6 sampai 12
            $kedatangan_between_6_12 = Transaksi::whereTime('waktu_transaksi','>','06:00:00')->whereTime('waktu_transaksi','<=','12:00:00')->count();

            // Count transaksi antara pukul 12 sampai 18
            $kedatangan_between_12_18 = Transaksi::whereTime('waktu_transaksi','>','12:00:00')->whereTime('waktu_transaksi','<=','18:00:00')->count();

            // Count transaksi antara pukul 18 sampai 24
            $kedatangan_between_18_24 = Transaksi::whereTime('waktu_transaksi','>','18:00:00')->whereTime('waktu_transaksi','<=','23:59:59')->count();
        }
        elseif($request->query('t1') != null && $request->query('t2') != null){
            // Count transaksi antara pukul 0 sampai 6
            $kedatangan_between_0_6 = Transaksi::whereDate('waktu_transaksi','>=',generate_date_format(generate_date_slash($request->query('t1')), 'y-m-d'))->whereDate('waktu_transaksi','<=',generate_date_format(generate_date_slash($request->query('t2')),'y-m-d'))->whereTime('waktu_transaksi','>=','00:00:00')->whereTime('waktu_transaksi','<=','06:00:00')->count();

            // Count transaksi antara pukul 6 sampai 12
            $kedatangan_between_6_12 = Transaksi::whereDate('waktu_transaksi','>=',generate_date_format(generate_date_slash($request->query('t1')),'y-m-d'))->whereDate('waktu_transaksi','<=',generate_date_format(generate_date_slash($request->query('t2')),'y-m-d'))->whereTime('waktu_transaksi','>','06:00:00')->whereTime('waktu_transaksi','<=','12:00:00')->count();

            // Count transaksi antara pukul 12 sampai 18
            $kedatangan_between_12_18 = Transaksi::whereDate('waktu_transaksi','>=',generate_date_format(generate_date_slash($request->query('t1')),'y-m-d'))->whereDate('waktu_transaksi','<=',generate_date_format(generate_date_slash($request->query('t2')),'y-m-d'))->whereTime('waktu_transaksi','>','12:00:00')->whereTime('waktu_transaksi','<=','18:00:00')->count();

            // Count transaksi antara pukul 18 sampai 24
            $kedatangan_between_18_24 = Transaksi::whereDate('waktu_transaksi','>=',generate_date_format(generate_date_slash($request->query('t1')),'y-m-d'))->whereDate('waktu_transaksi','<=',generate_date_format(generate_date_slash($request->query('t2')),'y-m-d'))->whereTime('waktu_transaksi','>','18:00:00')->whereTime('waktu_transaksi','<=','23:59:59')->count();
        }

        // Push data
        $data['data'] = [
            $kedatangan_between_0_6,
            $kedatangan_between_6_12,
            $kedatangan_between_12_18,
            $kedatangan_between_18_24,
        ];

        // Push label
        $data['label'] = ['0-6', '6-12', '12-18', '18-24'];

        // Push background color
        $data['bgcolor'] = [
            $this->colors['info'],
            $this->colors['warning'],
            $this->colors['success'],
            $this->colors['danger'],
        ];

        echo json_encode($data);
    }

    /**
     * Menampilkan best customer
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function best_customer(Request $request)
    {
        if($request->query('bulan') != null && $request->query('tahun') != null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('nama_user, username, SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja > 75000) $customer->push($data);
			  }
			});
		}
        elseif($request->query('bulan') == null && $request->query('tahun') == null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',date('n'))->whereYear('waktu_transaksi','=',date('Y'))->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja > 75000) $customer->push($data);
			  }
			});
		}
			
        return view('admin/report/best-customer', [
            'bulan' => $this->bulan,
			'customer' => $customer,
        ]);
    }

    /**
     * Menampilkan middle customer
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function middle_customer(Request $request)
    {
        if($request->query('bulan') != null && $request->query('tahun') != null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('nama_user, username, SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja <= 75000 && $data->belanja >= 40000) $customer->push($data);
			  }
			});
		}
        elseif($request->query('bulan') == null && $request->query('tahun') == null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',date('n'))->whereYear('waktu_transaksi','=',date('Y'))->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja <= 75000 && $data->belanja >= 40000) $customer->push($data);
			  }
			});
		}
			
        return view('admin/report/middle-customer', [
            'bulan' => $this->bulan,
			'customer' => $customer,
        ]);
    }

    /**
     * Menampilkan low customer
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function low_customer(Request $request)
    {
        if($request->query('bulan') != null && $request->query('tahun') != null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('nama_user, username, SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja < 40000) $customer->push($data);
			  }
			});
		}
        elseif($request->query('bulan') == null && $request->query('tahun') == null){
			$customer = collect();
            // Get user yang transaksi berdasarkan bulan dan tahun
            $user = User::join('transaksi','users.id_user','=','transaksi.id_user')->select(DB::raw('SUM(nominal_transaksi) AS belanja'))->where('jenis_transaksi','=',2)->whereMonth('waktu_transaksi','=',date('n'))->whereYear('waktu_transaksi','=',date('Y'))->groupBy('transaksi.id_user')->orderBy('belanja','desc')->chunk(100, function($rows) use(&$customer){
			  foreach($rows as $key=>$data){
			 	if($data->belanja < 40000) $customer->push($data);
			  }
			});
		}
			
        return view('admin/report/low-customer', [
            'bulan' => $this->bulan,
			'customer' => $customer,
        ]);
    }

  /**
   * Menampilkan statistik top up
   * 
   * @return \Illuminate\Http\Response
   */
  public function statistik_top_up()
  {
	  return view('admin/statistik/top-up', [
		  'bulan' => $this->bulan,
	  ]);
  }
	
  /**
   * Data grafik transaksi top up
   * 
   * @return \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
  public function graph_top_up(Request $request)
  {
    // Get bulan
    $bulan = $this->bulan[$request->bulan-1];

    $labels = [];
    $data_transaksi = [];
    $bulan[1] = $request->bulan == 2 && $request->tahun % 4 == 0 ? $bulan[1]+1 : $bulan[1];
    for($i = 1; $i <= $bulan[1]; $i++){
      // Get transaksi top up
      $transaksi = Transaksi::where('jenis_transaksi','=',1)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->sum('nominal_transaksi');

      // Push data
      array_push($labels, $i);
      array_push($data_transaksi, $transaksi);
    }

    $datasets = [
      [
        'label' => 'Top Up',
        'data' => $data_transaksi,
        'backgroundColor' => $this->colors['success'],
        'borderColor' => $this->colors['success'],
        'fill' => false,
        'borderWidth' => 1,
      ],
    ];

    $data['labels'] = $labels;
    $data['datasets'] = $datasets;
    $data['total'] = array_sum($data_transaksi);
    $data['bulan'] = $bulan[0];
    $data['tahun'] = $request->tahun;

    echo json_encode($data);
  }

  /**
   * Menampilkan statistik trx
   * 
   * @return \Illuminate\Http\Response
   */
  public function statistik_trx()
  {
	  return view('admin/statistik/trx', [
		  'bulan' => $this->bulan,
	  ]);
  }
	
  /**
   * Data grafik transaksi trx
   * 
   * @return \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
  public function graph_trx(Request $request)
  {
    // Get bulan
    $bulan = $this->bulan[$request->bulan-1];

    $labels = [];
    $data_transaksi = [];
    $bulan[1] = $request->bulan == 2 && $request->tahun % 4 == 0 ? $bulan[1]+1 : $bulan[1];
    for($i = 1; $i <= $bulan[1]; $i++){
      // Get transaksi trx
      $transaksi = Transaksi::where('jenis_transaksi','=',2)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->sum('nominal_transaksi');

      // Push data
      array_push($labels, $i);
      array_push($data_transaksi, $transaksi);
    }

    $datasets = [
      [
        'label' => 'TRX',
        'data' => $data_transaksi,
        'backgroundColor' => $this->colors['danger'],
        'borderColor' => $this->colors['danger'],
        'fill' => false,
        'borderWidth' => 1,
      ],
    ];

    $data['labels'] = $labels;
    $data['datasets'] = $datasets;
    $data['total'] = array_sum($data_transaksi);
    $data['bulan'] = $bulan[0];
    $data['tahun'] = $request->tahun;

    echo json_encode($data);
  }

  /**
   * Menampilkan statistik arpu
   * 
   * @return \Illuminate\Http\Response
   */
  public function statistik_arpu()
  {
	  return view('admin/statistik/arpu', [
		  'bulan' => $this->bulan,
	  ]);
  }
	
  /**
   * Data grafik transaksi arpu
   * 
   * @return \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
  public function graph_arpu(Request $request)
  {
    // Get bulan
    $bulan = $this->bulan[$request->bulan-1];

    $labels = [];
    $data_transaksi = [];
    $bulan[1] = $request->bulan == 2 && $request->tahun % 4 == 0 ? $bulan[1]+1 : $bulan[1];
    for($i = 1; $i <= $bulan[1]; $i++){
      // Get transaksi trx
      $transaksi = Transaksi::where('jenis_transaksi','=',2)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->sum('nominal_transaksi');
		
	  // Get user
      $user = Transaksi::where('jenis_transaksi','=',2)->whereDay('waktu_transaksi','=',$i)->whereMonth('waktu_transaksi','=',$request->bulan)->whereYear('waktu_transaksi','=',$request->tahun)->groupBy('id_user')->count();
		
	  // ARPU
	  $arpu = $user > 0 ? $transaksi / $user : 0;

      // Push data
      array_push($labels, $i);
      array_push($data_transaksi, $arpu);
    }

    $datasets = [
      [
        'label' => 'ARPU',
        'data' => $data_transaksi,
        'backgroundColor' => $this->colors['info'],
        'borderColor' => $this->colors['info'],
        'fill' => false,
        'borderWidth' => 1,
      ],
    ];

    $data['labels'] = $labels;
    $data['datasets'] = $datasets;
    $data['total'] = array_sum($data_transaksi) / $bulan[1];
    $data['bulan'] = $bulan[0];
    $data['tahun'] = $request->tahun;

    echo json_encode($data);
  }
}
