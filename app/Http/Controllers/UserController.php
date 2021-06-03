<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Role;
use App\User;

class UserController extends Controller
{
    /**
     * Menampilkan data user
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data user
        $users = User::join('pekerjaan','users.pekerjaan','=','pekerjaan.id_pekerjaan')->where('role','=',role_member())->get();

        // Get data user berulang tahun
        $birthday_users = User::where('role','=',role_member())->whereDay('tanggal_lahir','=',date('d'))->whereMonth('tanggal_lahir','=',date('m'))->orderBy('tanggal_lahir','asc')->get();

        // View
        return view('admin/user/index', [
            'users' => $users,
            'birthday_users' => $birthday_users,
        ]);
    }

    /**
     * Menambah data user
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // View
        return view('admin/user/create');
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
            'nama' => 'required|string|max:255',
            'username' => 'required|numeric|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_hp' => 'required|numeric',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan data
            $user = new User;
            $user->nama_user = $request->nama;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = '';
            $user->nomor_hp = $request->nomor_hp;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->pekerjaan = $request->pekerjaan != '' ? $request->pekerjaan : '';
            $user->role = role_member();
            $user->register_at = date('Y-m-d H:i:s');
            $user->save();
        }

        // Redirect
        return redirect('/admin/user')->with(['message' => 'Berhasil menambah data.']);
    }
    
    /**
     * Menampilkan profil admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        // Get data admin
        $admin = User::join('role','users.role','=','role.id_role')->where('role','=',role_admin())->find(Auth::user()->id_user);

        if(!$admin){
            abort(404);
        }

        // View
        return view('admin/user/profile', [
            'admin' => $admin,
        ]);
    }

    /**
     * Mengedit data user
     * 
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data user
        $user = User::join('role','users.role','=','role.id_role')->where('role','=',role_member())->find($id);

        // Jika tidak ada user
        if(!$user){
            abort(404);
        }

        // View
        return view('admin/user/edit', [
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
            'nama' => 'required|string|max:255',
            'username' => ['required', 'numeric', Rule::unique('users')->ignore($request->id, 'id_user')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->id, 'id_user')],
            'nomor_hp' => 'required|numeric',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan data
            $user = User::find($request->id);
            $user->nama_user = $request->nama;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->nomor_hp = $request->nomor_hp;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->pekerjaan = $request->pekerjaan != '' ? $request->pekerjaan : '';
            $user->save();
        }

        // Redirect
        return redirect('/admin/user')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Mengupdate data user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => ['required', 'alpha_dash', Rule::unique('users')->ignore($request->id, 'id_user')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->id, 'id_user')],
            'password' => $request->password != '' ? 'required|string|min:4' : '',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan data
            $user = User::find($request->id);
            $user->nama_user = $request->nama;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->save();
        }

        // Redirect
        return redirect('/admin/profil')->with(['message' => 'Berhasil mengupdate data.']);
    }
    
    /**
     * Menghapus user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus user
        $user = User::find($request->id);
        $user->delete();

        // Redirect
        return redirect('/admin/user')->with(['message' => 'Berhasil menghapus data.']);
    }
    
    /**
     * Export ke Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
		ini_set("memory_limit", "-1");
		
        // Get data user
        $users = User::join('pekerjaan','users.pekerjaan','=','pekerjaan.id_pekerjaan')->where('role','=',role_member())->get();

        return Excel::download(new UserExport($users), 'Data User.xlsx');
    }
 
    /**
     * Import dari Excel
     *
     * @return \Illuminate\Http\Response
     */
	public function import(Request $request) 
	{        
        ini_set('max_execution_time', 600);

        // Mengkonversi data di Excel ke bentuk array
        $array = Excel::toArray(new UserImport, $request->file('file'));

        if(count($array)>0){
            foreach($array[0] as $data){
                // Mengecek data user berdasarkan id
                $user = User::where('role','=',role_member())->find($data[9]);

                // Jika data user tidak ditemukan
                if(!$user){
                    $check_user = User::where('role','=',role_member())->where('username','=',$data[2])->first();

                    if(!$check_user){
                        // Konversi format tanggal
                        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[5]);
                        $date = (array)$date;
                        
                        // Tambah data user
                        $user = new User;
                        $user->tanggal_lahir = date('Y-m-d', strtotime($date['date']));
                        $user->role = role_member();
                        $user->register_at = date('Y-m-d H:i:s');

                        // Menyimpan atau mengupdate user
                        $user->nama_user = $data[1];
                        $user->username = $data[2];
                        $user->email = $data[3] != null ? $data[3] : '';
                        $user->password = '';
                        $user->nomor_hp = $data[4];
                        $user->jenis_kelamin = $data[6];
                        $user->pekerjaan = get_pekerjaan($data[7]);
                        $user->pendaftaran = get_pendaftaran($data[8]);
                        $user->save();
                    }
                }
                else{
                    $user->tanggal_lahir = generate_date_format($data[5], 'y-m-d');

                    // Menyimpan atau mengupdate user
                    $user->nama_user = $data[1];
                    $user->username = $data[2];
                    $user->email = $data[3] != null ? $data[3] : '';
                    $user->password = '';
                    $user->nomor_hp = $data[4];
                    $user->jenis_kelamin = $data[6];
                    $user->pekerjaan = get_pekerjaan($data[7]);
                    $user->pendaftaran = get_pendaftaran($data[8]);
                    $user->save();
                }
                //
            }

            // Redirect
            return redirect('/admin/user')->with(['message' => 'Berhasil mengimport data.']);
        }
        else{
            // Redirect
            return redirect('/admin/user')->with(['message' => 'Data di Excel kosong.']);
        }
	}
}
