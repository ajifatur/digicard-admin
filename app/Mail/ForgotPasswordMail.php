<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * int id pelamar
     * @return void
     */
    public function __construct($id)
    {
        $this->id_user = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // CS
        $cs = User::where('role','=',role_cs())->first();

		// Get data user
		$user = User::find($this->id_user);
		
		// Change password
		$new_password = shuffle_string(8);
		$user->password = bcrypt($new_password);
		$user->save();
		
        return $this->from($cs->email, get_website_name())->markdown('email/forgot-password')->subject('Recovery Password '.get_website_name())->with([
            'user' => $user,
			'new_password' => $new_password
        ]);
    }
}
