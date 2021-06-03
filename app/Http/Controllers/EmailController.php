<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmailImport;
use App\Mail\MessageMail;
use App\Email;
use App\User;

class EmailController extends Controller
{
    /**
     * Menampilkan data email
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data email
        $email = Email::join('users','email.id_pengirim','=','users.id_user')->orderBy('email_at','desc')->get();

        // View
        return view('admin/email/index', [
            'email' => $email,
        ]);
    }

    /**
     * Menambah data email
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get data user
        $user = User::where('role','=',role_member())->get();

        // View
        return view('admin/email/create', [
            'user' => $user
        ]);
    }

    /**
     * Import dari Excel
     * 
     * @return \Illuminate\Http\Response
     */
	public function import(Request $request) 
	{		
		echo json_encode(Excel::toArray(new EmailImport, $request->file('file')));
	}

    /**
     * Mengirim dan menyimpan pesan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'subjek' => 'required|max:255',
        ], validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput($request->only([
                'subjek',
            ]));
        }
        // Jika tidak ada error
        else{			
            // Upload gambar dari quill
            $html = quill_image_upload($request->konten, 'assets/images/konten-email/');
			
			if($request->ids != ""){
				// Explode
				$ids = explode(",", $request->ids);
				$emails = explode(", ", $request->emails);

				// Send Mail
				foreach($ids as $id){
				$receiver = User::find($id);
				Mail::to($receiver->email)->send(new MessageMail(Auth::user()->email, $receiver, $request->subjek, htmlentities($html)));
				}
			}
			else{
				// Explode
				$names = explode(", ", $request->names);
				$emails = explode(", ", $request->emails);

				// Send Mail
				foreach($emails as $key=>$email){
					Mail::to($email)->send(new MessageMail(Auth::user()->email, $names[$key], $request->subjek, htmlentities($html)));
				}
			}
			
			// Menyimpan pesan
			$mail = new Email;
			$mail->id_pengirim = Auth::user()->id_user;
			$mail->id_penerima = $request->ids != '' ? $request->ids : '';
			$mail->subjek = $request->subjek;
			$mail->email_penerima = $request->emails;
			$mail->konten = htmlentities($html);
			$mail->email_at = date('Y-m-d H:i:s');
			$mail->save();
        }

        // Redirect
        return redirect('/admin/email')->with(['message' => 'Berhasil mengirim pesan.']);
    }
	
    /**
     * Menampilkan detail email
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Data email
        $email = Email::join('users','email.id_pengirim','=','users.id_user')->find($id);

        if(!$email){
            abort(404);
        }

        // Get data user
        $user = User::where('role','=',role_member())->get();

        // View
        return view('admin/email/detail', [
            'email' => $email,
            'user' => $user,
        ]);
    }
    
    /**
     * Menghapus email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus email
        $email = Email::find($request->id);
        $email->delete();

        // Redirect
        return redirect('/admin/email')->with(['message' => 'Berhasil menghapus data.']);
    }
}
