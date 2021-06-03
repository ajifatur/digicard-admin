<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgotPasswordMail;
use App\Setting;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        // Get URLs
        $urlPrevious = url()->previous();
        $urlBase = url()->to('/');
		
        // If admin came from login, remove session url.intended
        if((session()->get('url.intended') == '/admin/logout')){
            session()->forget('url.intended');
        }
        // Set the previous url that we came from to redirect to after successful login but only if is internal
        elseif(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase) && ((substr($urlPrevious, strlen($urlBase), 6) == '/admin'))) {
            session()->put('url.intended', $urlPrevious);

            // View
            return view('auth/login', ['message' => 'Anda harus login terlebih dahulu!']);
        }

        // View
        return view('auth/login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4',
            'password' => 'required|string|min:4',
        ], validation_messages());
		
		$loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		
		$login = [
			$loginType => $request->username,
			'password' => $request->password
		];
		
		if (auth()->attempt($login)) {
			return $this->sendLoginResponse($request);
		}
		
		$this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {        
        if(session()->get('url.intended') != null){
            return redirect()->intended();
        }

        // Redirect
        return redirect('/admin');
    }

    /**
     * Recovery Password Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRecoveryPasswordForm(Request $request)
    {
        // View
        return view('auth/recovery-password-form');
    }

    /**
     * Recovery Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function recoveryPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], validation_messages());
		
		// Get data user
		$user = User::where('email','=',$request->email)->first();
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
			if(!$user){
				// Redirect
				return redirect('/recovery-password')->with(['message' => 'Email yang Anda masukkan tidak tersedia!']);
			}
			else{
				// Send Mail
            	Mail::to($request->email)->send(new ForgotPasswordMail($user->id_user));
				
				// Redirect
				return redirect('/recovery-password')->with(['message' => 'Permintaan berhasil. Silahkan cek email dan ikuti instruksi recovery password. Periksa juga folder spam jika email belum muncul.']);
			}
		}
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
