<?php

/*--------------------------------------------------------------------------------------------*/
/* ROLES */
/*--------------------------------------------------------------------------------------------*/

// Get role Admin
if(!function_exists('role_admin')){
    function role_admin(){
        return 1;
    }
}

// Get role Member
if(!function_exists('role_member')){
    function role_member(){
        return 2;
    }
}

/*--------------------------------------------------------------------------------------------*/
/* SETTINGS */
/*--------------------------------------------------------------------------------------------*/

// Get nama website
if(!function_exists('get_website_name')){
    function get_website_name(){  
        $data = DB::table('settings')->where('setting_key','website_name')->first();
        return $data->setting_value; 
    }
}

// Get tagline
if(!function_exists('get_tagline')){
    function get_tagline(){  
        $data = DB::table('settings')->where('setting_key','tagline')->first();
        return $data->setting_value; 
    }
}

// Get logo
if(!function_exists('get_logo')){
    function get_logo(){  
        $data = DB::table('settings')->where('setting_key','logo')->first();
        return $data->setting_value; 
    }
}

// Get icon
if(!function_exists('get_icon')){
    function get_icon(){  
        $data = DB::table('settings')->where('setting_key','icon')->first();
        return $data->setting_value; 
    }
}

// Get alamat
if(!function_exists('get_alamat')){
    function get_alamat(){  
        $data = DB::table('kontak')->first();
        return $data->alamat; 
    }
}

// Get nomor telepon
if(!function_exists('get_nomor_telepon')){
    function get_nomor_telepon(){  
        $data = DB::table('kontak')->first();
        return $data->no_telepon; 
    }
}

// Get email
if(!function_exists('get_email')){
    function get_email(){  
        $data = DB::table('kontak')->first();
        return $data->email; 
    }
}

// Get peta
if(!function_exists('get_peta')){
    function get_peta(){  
        $data = DB::table('kontak')->first();
        return $data->peta; 
    }
}

/*--------------------------------------------------------------------------------------------*/
/* GET DATA */
/*--------------------------------------------------------------------------------------------*/

// Get pekerjaan
if(!function_exists('get_pekerjaan')){
    function get_pekerjaan($pekerjaan = null){
        $data = DB::table('pekerjaan')->where('nama_pekerjaan','=',$pekerjaan)->first();
        return $data != null ? $data->id_pekerjaan : 0;
    }
}

// Get pendaftaran
if(!function_exists('get_pendaftaran')){
    function get_pendaftaran($pendaftaran = null){
        $data = DB::table('pendaftaran')->where('tempat_pendaftaran','=',$pendaftaran)->first();
        return $data != null ? $data->id_pendaftaran : 0;
    }
}

// Get data slider
if(!function_exists('get_data_slider')){
    function get_data_slider(){
        $data = DB::table('slider')->where('slider_status','=',1)->orderBy('id_slider','desc')->get();
        return count($data) > 0 ? $data : [];
    }
}

// Get data jurusan
if(!function_exists('get_data_jurusan')){
    function get_data_jurusan(){
        $data = DB::table('jurusan')->get();
        return count($data) > 0 ? $data : [];
    }
}

// Get data pop-up
if(!function_exists('get_data_popup')){
    function get_data_popup(){
        $data = DB::table('popup')->where('popup_status','=',1)->orderBy('popup_at','desc')->get();
        return count($data) > 0 ? $data : [];
    }
}

// Get data selayang pandang
if(!function_exists('get_data_selayang_pandang')){
    function get_data_selayang_pandang(){
        $data = DB::table('selayang_pandang')->first();
        return $data;
    }
}

// Get data pos
if(!function_exists('get_data_pos')){
    function get_data_pos($limit = null, $category = null, $paginate = null, $tag = null, $month = null, $year = null){
        if($limit == null && $category == null){
            if($paginate != null){
                if($month != null && $year != null)
                    $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->whereMonth('pos_at','=',$month)->whereYear('pos_at','=',$year)->orderBy('pos_at','desc')->paginate($paginate);
                else
                    $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->orderBy('pos_at','desc')->paginate($paginate);
            }
            else
                $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->orderBy('pos_at','desc')->get();
        }
        elseif($limit == null && $category != null){
            if($paginate != null)
                $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->where('slug','=',$category)->orderBy('pos_at','desc')->paginate($paginate);
            else
                $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->where('slug','=',$category)->orderBy('pos_at','desc')->get();
        }
        elseif($limit != null && $category == null)
            $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->orderBy('pos_at','desc')->limit($limit)->get();
        elseif($limit != null && $category != null)
            $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->where('slug','=',$category)->orderBy('pos_at','desc')->limit($limit)->get();

        if($tag != null){
            if(count($data)>0){
                foreach($data as $key=>$value){
                    if($value->pos_tag != ''){
                        $tags = explode(",", $value->pos_tag);
                        if(!in_array($tag, $tags)) $data->forget($key);
                    }
                    else{
                        $data->forget($key);
                    }
                }
            }
        }

        return count($data) > 0 ? $data : [];
    }
}

// Get data kategori pos
if(!function_exists('get_data_kategori_pos')){
    function get_data_kategori_pos($limit = null){
        if($limit == null){
            $data = DB::table('kategori_pos')->where('id_kp','!=',0)->get();

            $uncategorized = DB::table('kategori_pos')->where('id_kp','=',0)->first();

            $data->push($uncategorized);
        }
        else
            $data = DB::table('kategori_pos')->where('id_kp','!=',0)->limit($limit)->get();
        return count($data) > 0 ? $data : [];
    }
}

// Get data tag pos
if(!function_exists('get_data_tag_pos')){
    function get_data_tag_pos($limit = null){
        if($limit == null)
            $data = DB::table('tag')->get();
        else
            $data = DB::table('tag')->limit($limit)->get();
        return count($data) > 0 ? $data : [];
    }
}

// Get data galeri
if(!function_exists('get_data_galeri')){
    function get_data_galeri($limit = null, $paginate = null){
        if($limit == null && $paginate == null)
            $data = DB::table('album')->orderBy('album_at','desc')->get();
        elseif($limit != null)
            $data = DB::table('album')->orderBy('album_at','desc')->limit($limit)->get();
        elseif($paginate != null)
            $data = DB::table('album')->orderBy('album_at','desc')->paginate($paginate);

        if(count($data)>0){
            foreach($data as $key=>$value){
                $foto = DB::table('foto')->where('id_album','=',$value->id_album)->get();
                $data[$key]->cover = count($foto) > 0 ? $foto[0]->nama_foto : '';
                $data[$key]->jumlah = count($foto);
            }
        }

        return count($data) > 0 ? $data : [];
    }
}

// Get data foto
if(!function_exists('get_data_foto')){
    function get_data_foto($id, $limit = null, $paginate = null){
        if($limit == null && $paginate == null)
            $data = DB::table('foto')->where('id_album','=',$id)->get();
        elseif($limit != null)
            $data = DB::table('foto')->where('id_album','=',$id)->limit($limit)->get();
        elseif($paginate != null)
            $data = DB::table('foto')->where('id_album','=',$id)->paginate($paginate);
        return count($data) > 0 ? $data : [];
    }
}

// Get data video
if(!function_exists('get_data_video')){
    function get_data_video($limit = null, $paginate = null){
        if($limit == null && $paginate == null)
            $data = DB::table('video')->orderBy('video_at','desc')->get();
        elseif($limit != null)
            $data = DB::table('video')->orderBy('video_at','desc')->limit($limit)->get();
        elseif($paginate != null)
            $data = DB::table('video')->orderBy('video_at','desc')->paginate($paginate);
        return count($data) > 0 ? $data : [];
    }
}

// Get data pencarian pos
if(!function_exists('search_data_pos')){
    function search_data_pos($keyword, $paginate){
        $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->where('pos_judul','like','%'.$keyword.'%')->orderBy('pos_at','desc')->paginate($paginate);
        return count($data) > 0 ? $data : [];
    }
}

// Get data pencarian galeri
if(!function_exists('search_data_galeri')){
    function search_data_galeri($keyword, $paginate){
        $data = DB::table('album')->where('album','like','%'.$keyword.'%')->orderBy('album_at','desc')->paginate($paginate);

        if(count($data)>0){
            foreach($data as $key=>$value){
                $foto = DB::table('foto')->where('id_album','=',$value->id_album)->get();
                $data[$key]->cover = count($foto) > 0 ? $foto[0]->nama_foto : '';
                $data[$key]->jumlah = count($foto);
            }
        }

        return count($data) > 0 ? $data : [];
    }
}

// Get data pencarian video
if(!function_exists('search_data_video')){
    function search_data_video($keyword, $paginate){
        $data = DB::table('video')->where('video_judul','like','%'.$keyword.'%')->orderBy('video_at','desc')->paginate($paginate);
        return count($data) > 0 ? $data : [];
    }
}

// Get data pencarian halaman
if(!function_exists('search_data_halaman')){
    function search_data_halaman($keyword, $paginate){
        $data = DB::table('halaman')->join('kategori_halaman','halaman.halaman_kategori','=','kategori_halaman.id_kh')->where('halaman_judul','like','%'.$keyword.'%')->orderBy('halaman_at','desc')->paginate($paginate);
        return count($data) > 0 ? $data : [];
    }
}

/*--------------------------------------------------------------------------------------------*/
/* GET THIS DATA */
/*--------------------------------------------------------------------------------------------*/

// Get this halaman
if(!function_exists('get_this_halaman')){
    function get_this_halaman($id){
        $data = DB::table('halaman')->join('kategori_halaman','halaman.halaman_kategori','=','kategori_halaman.id_kh')->where('id_halaman','=',$id)->first();
        return $data;
    }
}

// Get this kategori halaman
if(!function_exists('get_this_kategori_halaman')){
    function get_this_kategori_halaman($id){
        $data = DB::table('kategori_halaman')->where('id_kh','=',$id)->first();
        return $data;
    }
}

// Get this pos
if(!function_exists('get_this_pos')){
    function get_this_pos($id){
        $data = DB::table('pos')->join('kategori_pos','pos.pos_kategori','=','kategori_pos.id_kp')->join('users','pos.pos_author','=','users.id_user')->where('id_pos','=',$id)->first();

        if($data->pos_tag != ''){
            $tags = explode(",", $data->pos_tag);
            if(count($tags)>0){
                foreach($tags as $key=>$value){
                    $data_tag = DB::table('tag')->where('id_tag','=',$value)->first();
                    $tags[$key] = $data_tag;
                }
                $data->pos_tag = $tags;
            }
        }
        else{
            $data->pos_tag = array();
        }

        return $data;
    }
}

// Get this kategori pos
if(!function_exists('get_this_kategori_pos')){
    function get_this_kategori_pos($id){
        $data = DB::table('kategori_pos')->where('id_kp','=',$id)->first();
        return $data;
    }
}

// Get this galeri
if(!function_exists('get_this_galeri')){
    function get_this_galeri($id){
        $data = DB::table('album')->where('id_album','=',$id)->first();
        return $data;
    }
}

/*--------------------------------------------------------------------------------------------*/
/* VALIDATION MESSAGES */
/*--------------------------------------------------------------------------------------------*/

// Pesan validasi form
if(!function_exists('validation_messages')){
    function validation_messages(){
        // Pesan Error
        $messages = [
            'alpha' => 'Hanya bisa diisi dengan huruf!',
            'alpha_dash' => 'Hanya bisa diisi dengan huruf, angka, strip dan underscore!',
            'confirmed' => 'Tidak cocok!',
            'max' => 'Harus diisi maksimal :max karakter!',
            'min' => 'Harus diisi minimal :min karakter!',
            'numeric' => 'Harus diisi dengan nomor atau angka!',
            'required' => 'Harus diisi!',
            'unique' => 'Sudah terdaftar!',
        ];
        
        return $messages;
    }
}

/*--------------------------------------------------------------------------------------------*/
/* ANOTHER SETTINGS */
/*--------------------------------------------------------------------------------------------*/

// Get setting rules
if(!function_exists('setting_rules')){
    function setting_rules($key){
        $data = DB::table('settings')->where('setting_key',$key)->first();
        return $data->setting_rules;  
    }
}

// Mengupload file content
if(!function_exists('upload_file_content')){
    function upload_file_content($file_src, $destination){
        list($type, $file_src) = explode(';', $file_src);
        list(, $file_src)      = explode(',', $file_src);
        $file_src = base64_decode($file_src);
        $mime = str_replace('data:', '', $type);
        $file_name = time().'.'.mime_to_ext($mime)[0];
        file_put_contents($destination.$file_name, $file_src);

        return $file_name;
    }
}

// Mengupload gambar dari Quill Editor
if(!function_exists('quill_image_upload')){
    function quill_image_upload($html, $img_path){
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $key=>$image){
            // Mengambil isi atribut "src"
            $src = $image->getAttribute('src');

			// Mencari gambar yang bukan URL
            if(filter_var($src, FILTER_VALIDATE_URL) == false){
                // Upload foto
                list($type, $src) = explode(';', $src);
                list(, $src)      = explode(',', $src);
                $src = base64_decode($src);
                $mime = str_replace('data:', '', $type);
                $image_name = time().'-'.($key+1).'.'.mime_to_ext($mime)[0];
                file_put_contents($img_path.$image_name, $src);

                // Mengganti atribut "src"
                $image->setAttribute('src', URL::to('/').'/'.$img_path.$image_name);
            }
        }
        
        return $dom->saveHTML();
    }
}

// Menghitung jumlah data
if(!function_exists('count_data')){
    function count_data($table, $field, $id){
        $data = DB::table($table)->where($field,'=',$id)->get();
        return count($data);
    }
}

// Menghitung jumlah data duplikat
if(!function_exists('count_existing_data')){
    function count_existing_data($table, $field, $keyword, $primaryKey, $id = null){
        if($id == null) $data = DB::table($table)->where($field,'=',$keyword)->get();
        else $data = DB::table($table)->where($field,'=',$keyword)->where($primaryKey,'!=',$id)->get();
        return count($data);
    }
}

// Menghitung jumlah data duplikat dengan kategori
if(!function_exists('count_existing_data_with_category')){
    function count_existing_data_with_category($table, $field, $keyword, $primaryKey, $id = null, $categoryField, $category){
        if($id == null) $data = DB::table($table)->where($field,'=',$keyword)->where($categoryField,'=',$category)->get();
        else $data = DB::table($table)->where($field,'=',$keyword)->where($categoryField,'=',$category)->where($primaryKey,'!=',$id)->get();
        return count($data);
    }
}

// Menghitung jumlah kunjungan visitor
if(!function_exists('count_visits')){
    function count_visits($user){
        $data = DB::table('visitor')->where('id_user','=',$user)->get();
        return count($data);
    }
}

// Menghitung jumlah komentar dalam pos
if(!function_exists('count_comments')){
    function count_comments($pos){
        $data = DB::table('komentar')->where('id_pos','=',$pos)->get();
        return count($data);
    }
}

// Menghitung jumlah kategori pos
if(!function_exists('count_post_categories')){
    function count_post_categories($category){
        $data = DB::table('pos')->where('pos_kategori','=',$category)->get();
        return count($data);
    }
}

// Mengganti nama permalink yang sama
if(!function_exists('rename_permalink')){
    function rename_permalink($permalink, $count){
        return $permalink."-".($count+1);
    }
}

// Menampilkan file yang tak terpakai
if(!function_exists('unused_files')){
    function unused_files($path, $array){
		$dir = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
		$array_file = array();
		if(is_dir($dir)){
			if($handle = opendir($dir)){
    			//tampilkan semua file dalam folder kecuali
           		while(($file = readdir($handle)) !== false){
					if(!in_array($file, $array)){
						if($file != '.' && $file != '..'){
							array_push($array_file, $file);
						}
					}
            	}
            	closedir($handle);
        	}
    	}
		return $array_file;
    }
}

// Array file
if(!function_exists('array_files')){
    function array_files($table, $field){
        $files = DB::table($table)->get();
		$array = array();
		foreach($files as $file){
            $data = get_object_vars($file);
			array_push($array, $data[$field]);
        }
        return $array;
    }
}

// Acak huruf
if(!function_exists('shuffle_string')){
    function shuffle_string($length){
        $string = '1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
        $shuffle = substr(str_shuffle($string), 0, $length);
        return $shuffle;
    }
}

/*--------------------------------------------------------------------------------------------*/
/* GENERATE */
/*--------------------------------------------------------------------------------------------*/

// Generate tanggal
if(!function_exists('generate_date')){
    function generate_date($date){
        $explode1 = explode(" ", $date);
        $explode2 = explode("-", $explode1[0]);
        $month = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        return $explode2[2]." ".$month[$explode2[1]-1]." ".$explode2[0];
    }
}

// Generate format tanggal
if(!function_exists('generate_date_format')){
    function generate_date_format($date, $format){
        if($format == 'd/m/y'){
            $explode = explode("-", $date);
            return $explode[2].'/'.$explode[1].'/'.$explode[0];
        }
        elseif($format == 'y-m-d'){
            $explode = explode("/", $date);
            return $explode[2].'-'.$explode[1].'-'.$explode[0];
        }
        else
            return '';
    }
}

// Generate slash tanggal
if(!function_exists('generate_date_slash')){
    function generate_date_slash($date){
        $tanggal = substr($date,0,2);
        $bulan = substr($date,2,2);
        $tahun = substr($date,4,4);
        return $tanggal."/".$bulan."/".$tahun;
    }
}

// Generate permalink
if(!function_exists('generate_permalink')){
    function generate_permalink($string){
        // Konversi string menjadi karakter kecil semua
        $result = strtolower($string);
        // Hanya menerima huruf, angka, spasi, dan strip
        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        // Menghapus spasi yang dobel
        $result = preg_replace("/\s+/", " ",$result);
        // Mengganti spasi menjadi strip
        $result = str_replace(" ", "-", $result);
        // Return
        return $result;
    }
}

// Generate ukuran file
if(!function_exists('generate_size')){
    function generate_size($bytes){ 
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if (($bytes >= 0) && ($bytes < $kb)) {
            return $bytes . ' B';
        } elseif (($bytes >= $kb) && ($bytes < $mb)) {
            return round($bytes / $kb) . ' KB';
        } elseif (($bytes >= $mb) && ($bytes < $gb)) {
            return round($bytes / $mb) . ' MB';
        } elseif (($bytes >= $gb) && ($bytes < $tb)) {
            return round($bytes / $gb) . ' GB';
        } elseif ($bytes >= $tb) {
            return round($bytes / $tb) . ' TB';
        } else {
            return $bytes . ' B';
        }
    }
}

// Generate warna
if(!function_exists('generate_color')){
    function generate_color($color){
        $hsl = rgb_to_hsl(html_to_rgb($color));
        if($hsl->lightness > 200) return '#000000';
        else return '#ffffff';
    }
}

// Generate umur / usia
if(!function_exists('generate_age')){
    function generate_age($date){
        $birthdate = new DateTime($date);
        $today = new DateTime('today');

        $y = $today->diff($birthdate)->y;
        return $y;
    }
}

// Generate bulan indo
if(!function_exists('generate_month_indo')){
    function generate_month_indo($month){
        $array = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return $array[$month-1];
    }
}

/*--------------------------------------------------------------------------------------------*/
/* CONVERTERS */
/*--------------------------------------------------------------------------------------------*/

// Konversi HTML ke RGB
if(!function_exists('html_to_rgb')){
    function html_to_rgb($code){
        if($code[0] == '#')
            $code = substr($code, 1);

        if(strlen($code) == 3){
            $code = $code[0] . $code[0] . $code[1] . $code[1] . $code[2] . $code[2];
        }

        $r = hexdec($code[0] . $code[1]);
        $g = hexdec($code[2] . $code[3]);
        $b = hexdec($code[4] . $code[5]);

        return $b + ($g << 0x8) + ($r << 0x10);
    }
}

// Konversi RGB ke HSL
if(!function_exists('rgb_to_hsl')){
    function rgb_to_hsl($code){
        $r = 0xFF & ($code >> 0x10);
        $g = 0xFF & ($code >> 0x8);
        $b = 0xFF & $code;

        $r = ((float)$r) / 255.0;
        $g = ((float)$g) / 255.0;
        $b = ((float)$b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if($maxC == $minC){
        $s = 0;
        $h = 0;
        }
        else{
            if($l < .5){
                $s = ($maxC - $minC) / ($maxC + $minC);
            }
            else{
                $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
            }

            if($r == $maxC)
                $h = ($g - $b) / ($maxC - $minC);
            if($g == $maxC)
                $h = 2.0 + ($b - $r) / ($maxC - $minC);
            if($b == $maxC)
                $h = 4.0 + ($r - $g) / ($maxC - $minC);

            $h = $h / 6.0; 
        }

        $h = (int)round(255.0 * $h);
        $s = (int)round(255.0 * $s);
        $l = (int)round(255.0 * $l);

        return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
    }
}

function HTMLToRGB($htmlCode)
  {
    if($htmlCode[0] == '#')
      $htmlCode = substr($htmlCode, 1);

    if (strlen($htmlCode) == 3)
    {
      $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    $r = hexdec($htmlCode[0] . $htmlCode[1]);
    $g = hexdec($htmlCode[2] . $htmlCode[3]);
    $b = hexdec($htmlCode[4] . $htmlCode[5]);

    return $b + ($g << 0x8) + ($r << 0x10);
  }

// Konversi MIME menjadi ekstensi
if(!function_exists('mime_to_ext')){
    function mime_to_ext($mime){
        $mime_map = [
            'video/3gpp2'                                                               => ['3g2', 'file-video', 'Video'],
            'video/3gp'                                                                 => ['3gp', 'file-video', 'Video'],
            'video/3gpp'                                                                => ['3gp', 'file-video', 'Video'],
            'application/x-compressed'                                                  => ['7zip', 'file-archive', 'Arsip'],
            'audio/x-acc'                                                               => ['aac', 'file-audio', 'Audio'],
            'audio/ac3'                                                                 => ['ac3', 'file-audio', 'Audio'],
            'application/postscript'                                                    => ['ai', 'file-alt', 'Lainnya'],
            'audio/x-aiff'                                                              => ['aif', 'file-audio', 'Audio'],
            'audio/aiff'                                                                => ['aif', 'file-audio', 'Audio'],
            'audio/x-au'                                                                => ['au', 'file-audio', 'Audio'],
            'video/x-msvideo'                                                           => ['avi', 'file-video', 'Video'],
            'video/msvideo'                                                             => ['avi', 'file-video', 'Video'],
            'video/avi'                                                                 => ['avi', 'file-video', 'Video'],
            'application/x-troff-msvideo'                                               => ['avi', 'file-video', 'Video'],
            'application/macbinary'                                                     => ['bin', 'file-alt', 'Lainnya', 'Lainnya'],
            'application/mac-binary'                                                    => ['bin', 'file-alt', 'Lainnya'],
            'application/x-binary'                                                      => ['bin', 'file-alt', 'Lainnya'],
            'application/x-macbinary'                                                   => ['bin', 'file-alt', 'Lainnya'],
            'image/bmp'                                                                 => ['bmp', 'file-image', 'Gambar'],
            'image/x-bmp'                                                               => ['bmp', 'file-image', 'Gambar'],
            'image/x-bitmap'                                                            => ['bmp', 'file-image', 'Gambar'],
            'image/x-xbitmap'                                                           => ['bmp', 'file-image', 'Gambar'],
            'image/x-win-bitmap'                                                        => ['bmp', 'file-image', 'Gambar'],
            'image/x-windows-bmp'                                                       => ['bmp', 'file-image', 'Gambar'],
            'image/ms-bmp'                                                              => ['bmp', 'file-image', 'Gambar'],
            'image/x-ms-bmp'                                                            => ['bmp', 'file-image', 'Gambar'],
            'application/bmp'                                                           => ['bmp', 'file-alt', 'Lainnya'],
            'application/x-bmp'                                                         => ['bmp', 'file-alt', 'Lainnya'],
            'application/x-win-bitmap'                                                  => ['bmp', 'file-alt', 'Lainnya'],
            'application/cdr'                                                           => ['cdr', 'file-alt', 'Lainnya'],
            'application/coreldraw'                                                     => ['cdr', 'file-alt', 'Lainnya'],
            'application/x-cdr'                                                         => ['cdr', 'file-alt', 'Lainnya'],
            'application/x-coreldraw'                                                   => ['cdr', 'file-alt', 'Lainnya'],
            'image/cdr'                                                                 => ['cdr', 'file-image', 'Gambar'],
            'image/x-cdr'                                                               => ['cdr', 'file-image', 'Gambar'],
            'zz-application/zz-winassoc-cdr'                                            => ['cdr', 'file-alt', 'Lainnya'],
            'application/mac-compactpro'                                                => ['cpt', 'file-alt', 'Lainnya'],
            'application/pkix-crl'                                                      => ['crl', 'file-alt', 'Lainnya'],
            'application/pkcs-crl'                                                      => ['crl', 'file-alt', 'Lainnya'],
            'application/x-x509-ca-cert'                                                => ['crt', 'file-alt', 'Lainnya'],
            'application/pkix-cert'                                                     => ['crt', 'file-alt', 'Lainnya'],
            'text/css'                                                                  => ['css', 'file-code', 'Source Code'],
            'text/x-comma-separated-values'                                             => ['csv', 'file-excel', 'Spreadsheet'],
            'text/comma-separated-values'                                               => ['csv', 'file-excel', 'Spreadsheet'],
            'application/vnd.msexcel'                                                   => ['csv', 'file-excel', 'Spreadsheet'],
            'application/x-director'                                                    => ['dcr', 'file-alt', 'Lainnya'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => ['docx', 'file-word', 'Dokumen'],
            'application/x-dvi'                                                         => ['dvi', 'file-alt', 'Lainnya'],
            'message/rfc822'                                                            => ['eml', 'file-alt', 'Lainnya'],
            'application/x-msdownload'                                                  => ['exe', 'file-alt', 'Lainnya'],
            'video/x-f4v'                                                               => ['f4v', 'file-video', 'Video'],
            'audio/x-flac'                                                              => ['flac', 'file-audio', 'Audio'],
            'video/x-flv'                                                               => ['flv', 'file-video', 'Video'],
            'image/gif'                                                                 => ['gif', 'file-image', 'Gambar'],
            'application/gpg-keys'                                                      => ['gpg', 'file-alt', 'Lainnya'],
            'application/x-gtar'                                                        => ['gtar', 'file-archive', 'Arsip'],
            'application/x-gzip'                                                        => ['gzip', 'file-archive', 'Arsip'],
            'application/mac-binhex40'                                                  => ['hqx', 'file-alt', 'Lainnya'],
            'application/mac-binhex'                                                    => ['hqx', 'file-alt', 'Lainnya'],
            'application/x-binhex40'                                                    => ['hqx', 'file-alt', 'Lainnya'],
            'application/x-mac-binhex40'                                                => ['hqx', 'file-alt', 'Lainnya'],
            'text/html'                                                                 => ['html', 'file-code', 'Source Code'],
            'image/x-icon'                                                              => ['ico', 'file-image', 'Gambar'],
            'image/x-ico'                                                               => ['ico', 'file-image', 'Gambar'],
            'image/vnd.microsoft.icon'                                                  => ['ico', 'file-image', 'Gambar'],
            'text/calendar'                                                             => ['ics', 'file-alt', 'Lainnya'],
            'application/java-archive'                                                  => ['jar', 'file-alt', 'Lainnya'],
            'application/x-java-application'                                            => ['jar', 'file-alt', 'Lainnya'],
            'application/x-jar'                                                         => ['jar', 'file-alt', 'Lainnya'],
            'image/jp2'                                                                 => ['jp2', 'file-image', 'Gambar'],
            'video/mj2'                                                                 => ['jp2', 'file-video', 'Video'],
            'image/jpx'                                                                 => ['jp2', 'file-image', 'Gambar'],
            'image/jpm'                                                                 => ['jp2', 'file-image', 'Gambar'],
            'image/jpeg'                                                                => ['jpeg', 'file-image', 'Gambar'],
            'image/pjpeg'                                                               => ['jpeg', 'file-image', 'Gambar'],
            'application/x-javascript'                                                  => ['js', 'file-code', 'Source Code'],
            'application/json'                                                          => ['json', 'file-alt', 'Lainnya'],
            'text/json'                                                                 => ['json', 'file-alt', 'Lainnya'],
            'application/vnd.google-earth.kml+xml'                                      => ['kml', 'file-alt', 'Lainnya'],
            'application/vnd.google-earth.kmz'                                          => ['kmz', 'file-alt', 'Lainnya'],
            'text/x-log'                                                                => ['log', 'file-alt', 'Lainnya'],
            'audio/x-m4a'                                                               => ['m4a', 'file-audio', 'Audio'],
            'audio/mp4'                                                                 => ['m4a', 'file-audio', 'Audio'],
            'application/vnd.mpegurl'                                                   => ['m4u', 'file-alt', 'Lainnya'],
            'audio/midi'                                                                => ['mid', 'file-audio', 'Audio'],
            'application/vnd.mif'                                                       => ['mif', 'file-alt', 'Lainnya'],
            'video/quicktime'                                                           => ['mov', 'file-video', 'Video'],
            'video/x-sgi-movie'                                                         => ['movie', 'file-video', 'Video'],
            'audio/mpeg'                                                                => ['mp3', 'file-audio', 'Audio'],
            'audio/mpg'                                                                 => ['mp3', 'file-audio', 'Audio'],
            'audio/mpeg3'                                                               => ['mp3', 'file-audio', 'Audio'],
            'audio/mp3'                                                                 => ['mp3', 'file-audio', 'Audio'],
            'video/mp4'                                                                 => ['mp4', 'file-video', 'Video'],
            'video/mpeg'                                                                => ['mpeg', 'file-video', 'Video'],
            'application/oda'                                                           => ['oda', 'file-alt', 'Lainnya'],
            'audio/ogg'                                                                 => ['ogg', 'file-audio', 'Audio'],
            'video/ogg'                                                                 => ['ogg', 'file-video', 'Video'],
            'application/ogg'                                                           => ['ogg', 'file-alt', 'Lainnya'],
            'application/x-pkcs10'                                                      => ['p10', 'file-alt', 'Lainnya'],
            'application/pkcs10'                                                        => ['p10', 'file-alt', 'Lainnya'],
            'application/x-pkcs12'                                                      => ['p12', 'file-alt', 'Lainnya'],
            'application/x-pkcs7-signature'                                             => ['p7a', 'file-alt', 'Lainnya'],
            'application/pkcs7-mime'                                                    => ['p7c', 'file-alt', 'Lainnya'],
            'application/x-pkcs7-mime'                                                  => ['p7c', 'file-alt', 'Lainnya'],
            'application/x-pkcs7-certreqresp'                                           => ['p7r', 'file-alt', 'Lainnya'],
            'application/pkcs7-signature'                                               => ['p7s', 'file-alt', 'Lainnya'],
            'application/pdf'                                                           => ['pdf', 'file-pdf', 'PDF'],
            'application/x-x509-user-cert'                                              => ['pem', 'file-alt', 'Lainnya'],
            'application/x-pem-file'                                                    => ['pem', 'file-alt', 'Lainnya'],
            'application/pgp'                                                           => ['pgp', 'file-alt', 'Lainnya'],
            'application/x-httpd-php'                                                   => ['php', 'file-code', 'Source Code'],
            'application/php'                                                           => ['php', 'file-code', 'Source Code'],
            'application/x-php'                                                         => ['php', 'file-code', 'Source Code'],
            'text/php'                                                                  => ['php', 'file-code', 'Source Code'],
            'text/x-php'                                                                => ['php', 'file-code', 'Source Code'],
            'application/x-httpd-php-source'                                            => ['php', 'file-code', 'Source Code'],
            'image/png'                                                                 => ['png', 'file-image', 'Gambar'],
            'image/x-png'                                                               => ['png', 'file-image', 'Gambar'],
            'application/powerpoint'                                                    => ['ppt', 'file-powerpoint', 'Power Point'],
            'application/vnd.ms-powerpoint'                                             => ['ppt', 'file-powerpoint', 'Power Point'],
            'application/vnd.ms-office'                                                 => ['ppt', 'file-powerpoint', 'Power Point'],
            'application/msword'                                                        => ['ppt', 'file-powerpoint', 'Power Point'],
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['pptx', 'file-powerpoint', 'Power Point'],
            'application/x-photoshop'                                                   => ['psd', 'file-alt', 'Lainnya'],
            'image/vnd.adobe.photoshop'                                                 => ['psd', 'file-alt', 'Lainnya'],
            'audio/x-realaudio'                                                         => ['ra', 'file-audio', 'Audio'],
            'audio/x-pn-realaudio'                                                      => ['ram', 'file-audio', 'Audio'],
            'application/x-rar'                                                         => ['rar', 'file-archive', 'Arsip'],
            'application/rar'                                                           => ['rar', 'file-archive', 'Arsip'],
            'application/x-rar-compressed'                                              => ['rar', 'file-archive', 'Arsip'],
            'application/octet-stream'                                                  => ['rar', 'file-archive', 'Arsip'],
            'audio/x-pn-realaudio-plugin'                                               => ['rpm', 'file-alt', 'Lainnya'],
            'application/x-pkcs7'                                                       => ['rsa', 'file-alt', 'Lainnya'],
            'text/rtf'                                                                  => ['rtf', 'file-alt', 'Lainnya'],
            'text/richtext'                                                             => ['rtx', 'file-alt', 'Lainnya'],
            'video/vnd.rn-realvideo'                                                    => ['rv', 'file-video', 'Video'],
            'application/x-stuffit'                                                     => ['sit', 'file-alt', 'Lainnya'],
            'application/smil'                                                          => ['smil', 'file-alt', 'Lainnya'],
            'text/srt'                                                                  => ['srt', 'file-alt', 'Lainnya'],
            'image/svg+xml'                                                             => ['svg', 'file-image', 'Gambar'],
            'application/x-shockwave-flash'                                             => ['swf', 'file-alt', 'Lainnya'],
            'application/x-tar'                                                         => ['tar', 'file-archive', 'Arsip'],
            'application/x-gzip-compressed'                                             => ['tgz', 'file-archive', 'Arsip'],
            'image/tiff'                                                                => ['tiff', 'file-alt', 'Lainnya'],
            'text/plain'                                                                => ['txt', 'file-alt', 'Lainnya'],
            'text/x-vcard'                                                              => ['vcf', 'file-alt', 'Lainnya'],
            'application/videolan'                                                      => ['vlc', 'file-alt', 'Lainnya'],
            'text/vtt'                                                                  => ['vtt', 'file-alt', 'Lainnya'],
            'audio/x-wav'                                                               => ['wav', 'file-audio', 'Audio'],
            'audio/wave'                                                                => ['wav', 'file-audio', 'Audio'],
            'audio/wav'                                                                 => ['wav', 'file-audio', 'Audio'],
            'application/wbxml'                                                         => ['wbxml', 'file-alt', 'Lainnya'],
            'video/webm'                                                                => ['webm', 'file-video', 'Video'],
            'image/webp'                                                                => ['webp', 'file-image', 'Gambar'],
            'audio/x-ms-wma'                                                            => ['wma', 'file-audio', 'Audio'],
            'application/wmlc'                                                          => ['wmlc', 'file-alt', 'Lainnya'],
            'video/x-ms-wmv'                                                            => ['wmv', 'file-video', 'Video'],
            'video/x-ms-asf'                                                            => ['wmv', 'file-video', 'Video'],
            'application/xhtml+xml'                                                     => ['xhtml', 'file-code', 'Source Code'],
            'application/excel'                                                         => ['xl', 'file-excel', 'Spreadsheet'],
            'application/msexcel'                                                       => ['xls', 'file-excel', 'Spreadsheet'],
            'application/x-msexcel'                                                     => ['xls', 'file-excel', 'Spreadsheet'],
            'application/x-ms-excel'                                                    => ['xls', 'file-excel', 'Spreadsheet'],
            'application/x-excel'                                                       => ['xls', 'file-excel', 'Spreadsheet'],
            'application/x-dos_ms_excel'                                                => ['xls', 'file-excel', 'Spreadsheet'],
            'application/xls'                                                           => ['xls', 'file-excel', 'Spreadsheet'],
            'application/x-xls'                                                         => ['xls', 'file-excel', 'Spreadsheet'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => ['xlsx', 'file-excel', 'Spreadsheet'],
            'application/vnd.ms-excel'                                                  => ['xlsx', 'file-excel', 'Spreadsheet'],
            'application/xml'                                                           => ['xml', 'file-alt', 'Lainnya'],
            'text/xml'                                                                  => ['xml', 'file-alt', 'Lainnya'],
            'text/xsl'                                                                  => ['xsl', 'file-alt', 'Lainnya'],
            'application/xspf+xml'                                                      => ['xspf', 'file-alt', 'Lainnya'],
            'application/x-compress'                                                    => ['z', 'file-archive', 'Arsip'],
            'application/x-zip'                                                         => ['zip', 'file-archive', 'Arsip'],
            'application/zip'                                                           => ['zip', 'file-archive', 'Arsip'],
            'application/x-zip-compressed'                                              => ['zip', 'file-archive', 'Arsip'],
            'application/s-compressed'                                                  => ['zip', 'file-archive', 'Arsip'],
            'multipart/x-zip'                                                           => ['zip', 'file-archive', 'Arsip'],
            'text/x-scriptzsh'                                                          => ['zsh', 'file-alt', 'Lainnya'],
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }
}