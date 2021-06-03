<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;
use App\Imports\TransaksiImport;
use App\Jobs\ImportJob;
use App\Transaksi;
use App\User;

class TransaksiController extends Controller
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
	
    /**
     * Menampilkan data transaksi
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->query('transaksi') != null && $request->query('bulan') != null && $request->query('tahun') != null){
			// Get data transaksi
			$transaksi = Transaksi::join('users','transaksi.id_user','=','users.id_user')->where('jenis_transaksi','=',$request->query('transaksi'))->whereMonth('waktu_transaksi','=',$request->query('bulan'))->whereYear('waktu_transaksi','=',$request->query('tahun'))->orderBy('waktu_transaksi','desc')->get();
		}
		else{
			// Get data transaksi
			$transaksi = Transaksi::join('users','transaksi.id_user','=','users.id_user')->where('jenis_transaksi','=',1)->whereMonth('waktu_transaksi','=',date('n'))->whereYear('waktu_transaksi','=',date('Y'))->orderBy('waktu_transaksi','desc')->get();
		}

        // View
        return view('admin/transaksi/index', [
            'transaksi' => $transaksi,
            'bulan' => $this->bulan,
        ]);
    }

    /**
     * Menambah data transaksi
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Data user
        $user = User::where('role','=',role_member())->get();

        // View
        return view('admin/transaksi/create', [
            'user' => $user
        ]);
    }

    /**
     * Menyimpan data user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_transaksi' => 'required',
            'tanggal_transaksi' => 'required',
            'jam_transaksi' => 'required',
            'nominal_transaksi' => 'required',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan data
            $transaksi = new Transaksi;
            $transaksi->id_user = $request->nama;
            $transaksi->waktu_transaksi = generate_date_format($request->tanggal_transaksi, 'y-m-d')." ".$request->jam_transaksi.":00";
            $transaksi->jenis_transaksi = $request->jenis_transaksi;
            $transaksi->nominal_transaksi = str_replace(".", "", $request->nominal_transaksi);
            $transaksi->transaksi_at = date('Y-m-d H:i:s');
            $transaksi->save();
        }

        // Redirect
        return redirect('/admin/transaksi')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Mengedit data transaksi
     * 
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data transaksi
        $transaksi = Transaksi::find($id);

        // Jika tidak ada transaksi
        if(!$transaksi){
            abort(404);
        }

        // Data user
        $user = User::where('role','=',role_member())->get();

        // View
        return view('admin/transaksi/edit', [
            'transaksi' => $transaksi,
            'user' => $user,
        ]);
    }

    /**
     * Mengupdate data user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_transaksi' => 'required',
            'tanggal_transaksi' => 'required',
            'jam_transaksi' => 'required',
            'nominal_transaksi' => 'required',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $transaksi = Transaksi::find($request->id);
            $transaksi->id_user = $request->nama;
            $transaksi->waktu_transaksi = generate_date_format($request->tanggal_transaksi, 'y-m-d')." ".$request->jam_transaksi.":00";
            $transaksi->jenis_transaksi = $request->jenis_transaksi;
            $transaksi->nominal_transaksi = str_replace(".", "", $request->nominal_transaksi);
            $transaksi->save();
        }

        // Redirect
        return redirect('/admin/transaksi')->with(['message' => 'Berhasil mengupdate data.']);
    }
    
    /**
     * Menghapus transaksi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus transaksi
        $transaksi = Transaksi::find($request->id);
        $transaksi->delete();

        // Redirect
        return redirect('/admin/transaksi')->with(['message' => 'Berhasil menghapus data.']);
    }
    
    /**
     * Export ke Excel
     *
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
		ini_set("memory_limit", "-1");
			
		// Bulan dan tahun
		$bulan = $request->query('bulan') != null ? $request->query('bulan') : date('n');
		$tahun = $request->query('tahun') != null ? $request->query('tahun') : date('Y');
		
		// Get data transaksi
		$transaksi = Transaksi::join('users','transaksi.id_user','=','users.id_user')->whereMonth('waktu_transaksi','=',$bulan)->whereYear('waktu_transaksi','=',$tahun)->orderBy('waktu_transaksi','asc')->get();

        return Excel::download(new TransaksiExport($transaksi), 'Data Transaksi ('.generate_month_indo($bulan).' '.$tahun.').xlsx');
    }
 
    /**
     * Import dari Excel
     *
     * @return \Illuminate\Http\Response
     */
	public function import(Request $request) 
	{        
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '2048M');
        ini_set('upload_max_filesize ', '512M');
        ini_set('post_max_size ', '514M');

        Excel::import(new TransaksiImport, $request->file('file'));

        // Redirect
        return redirect('/admin/transaksi')->with(['message' => 'Berhasil mengimport data.']);
	}
}
