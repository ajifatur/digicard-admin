<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Providers\RouteServiceProvider;
use App\Komisi;
use App\Setting;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {		
		// Get default referral code
		$setting = Setting::where('setting_key','=','default_referral_code')->first();
		$default = User::find($setting->setting_value);
        
        // Get referral
        $referral = $request->query('ref');
        if($referral == null){
            $user = null;
            $request->session()->put('ref', $default->username);
            return redirect('/register?ref='.$default->username);
        }
        else{
            $user = User::where('username',$referral)->where('status','=',1)->first();
            if(!$user){
                $request->session()->put('ref', $default->username);
                return redirect('/register?ref='.$default->username);
            }
            else{
                $request->session()->put('ref', $referral);
            }
        }
        // End get referral

        return view('front/'.template_app().'/register', [
			'default' => $default,
			'user' => $user,
		]);
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required'],
            'jenis_kelamin' => ['required'],
            'nomor_hp' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:6', 'max:255', 'unique:users', 'alpha_dash'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], validation_messages());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {		
		// Create user
		$user = new User;
		$user->nama_user = $data['nama_lengkap'];
		$user->username = $data['username'];
		$user->email = $data['email'];
		$user->password = Hash::make($data['password']);
		$user->tanggal_lahir = $data['tanggal_lahir'];
		$user->jenis_kelamin = $data['jenis_kelamin'];
		$user->nomor_hp = $data['nomor_hp'];
		$user->reference = $data['ref'];
		$user->foto = '';
		$user->role = role_member();
        $user->is_admin = 0;
		$user->status = 0;
        $user->last_visit = null;
		$user->saldo = 0;
		$user->register_at = date('Y-m-d H:i:s');
		$user->save();
		
		// Get data sponsor
		$user_sponsor = User::find($data['id_sponsor']);
		
		// Create data comission
		$new_user = User::where('username','=',$user->username)->first();
		$komisi = new Komisi;
		$komisi->id_user = $new_user->id_user;
		$komisi->id_sponsor = $data['id_sponsor'];
		$komisi->komisi_hasil = $user_sponsor->role == role_member() ? get_comission() : get_special_comission();
        $komisi->komisi_aktivasi = get_biaya_aktivasi();
        $komisi->komisi_status = 0;
        $komisi->komisi_proof = '';
		$komisi->masuk_ke_saldo = 0;
		$komisi->komisi_at = null;
        $komisi->inv_komisi = '';
		$komisi->save();

        // Generate invoice
        $new_komisi = Komisi::latest('id_komisi')->first();
        $new_komisi->inv_komisi = generate_invoice($new_komisi->id_komisi, 'KOM');
        $new_komisi->save();
		
		// Send Mail
		Mail::to($user->email)->send(new RegisterMail($new_user->id_user, $new_komisi->id_komisi));
		
		return $user;
    }
}
