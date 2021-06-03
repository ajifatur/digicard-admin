<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Role;
use App\Setting;
use App\User;

class SettingController extends Controller
{
    /**
     * Menampilkan form pengaturan
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Data pengaturan
        $settings = Setting::where('setting_category','=',1)->orderBy('id_setting','asc')->get();

        // View
        return view('admin/pengaturan/index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Mengupdate pengaturan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $key_rules = array();
        $settings = $request->get('settings');
        foreach($settings as $key=>$value){
            $key_rules['settings.'.$key] = setting_rules($key);
        }

        // Validasi
        $validator = Validator::make($request->all(), $key_rules, validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput($request->only([
                'settings.website_name',
                'settings.tagline',
            ]));
        }
        // Jika tidak ada error
        else{
            // Mengupdate
            foreach($settings as $key=>$value){
                // Get data
                $setting = Setting::where('setting_key',$key)->first();
                
                // Upload Image
                if($key == "icon" || $key == "logo"){
                    if($value != $setting->setting_value){
                        list($type, $value) = explode(';', $value);
                        list(, $value)      = explode(',', $value);
                        $value = base64_decode($value);
                        $mime = str_replace('data:', '', $type);
                        $file_name = time().'-'.$key.'.'.mime_to_ext($mime)[0];
                        file_put_contents("assets/images/logo/".$file_name, $value);
                        $value = $file_name;
                    }
                    else{
                        $value = $setting->setting_value;
                    }
                }

                // Save
                $setting->setting_value = $value;
            	$setting->save();
            }
        }

        // Redirect
        return redirect('/admin/pengaturan')->with(['message' => 'Berhasil mengupdate pengaturan.']);
    }
}
