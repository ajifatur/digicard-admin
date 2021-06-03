@extends('template/admin/main')

@section('title', 'Pengaturan')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-cog"></i> Pengaturan</h1>
      <p>Menu untuk melakukan pengaturan pada sistem</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/pengaturan">Pengaturan</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <form method="post" action="/admin/pengaturan/update">
            {{ csrf_field() }}
            <div class="tile-title-w-btn">
                <h3 class="title">Pengaturan</h3>
                <p><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></p>
            </div>
            <div class="tile-body">
                @if(Session::get('message') != null)
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                </div>
                @endif
                <div class="row">
                    @foreach($settings as $key=>$setting)
                        @if($settings[$key]->setting_key == 'logo' || $settings[$key]->setting_key == 'icon')
                        <div class="form-group col-md-6">
                            <label>{{ $settings[$key]->setting_name }} <span class="text-danger">{{ strpos($settings[$key]->setting_rules, 'required') ? '*' : '' }}</span></label>
                            <input type="file" id="{{ $settings[$key]->setting_key }}" class="file d-none">
                            <br>
                            <button class="btn btn-sm btn-primary btn-upload" data-id="{{ $settings[$key]->setting_key }}"><i class="fa fa-folder-open mr-2"></i>Upload</button>
                            <button class="btn btn-sm btn-danger btn-delete d-none" data-id="{{ $settings[$key]->setting_key }}"><i class="fa fa-trash mr-2"></i>Hapus</button>
                            <br>
                            <input type="hidden" name="settings[{{ $settings[$key]->setting_key }}]" class="src" data-id="{{ $settings[$key]->setting_key }}" data-old="{{ $settings[$key]->setting_value }}" value="{{ $settings[$key]->setting_value }}">
                            <img class="img-thumbnail mt-3" data-id="{{ $settings[$key]->setting_key }}" src="{{ asset('assets/images/logo/'.$settings[$key]->setting_value) }}" style="max-height: 250px;">
                        </div>
                        @else
                        <div class="form-group col-md-6">
                            <label>{{ $settings[$key]->setting_name }} <span class="text-danger">{{ is_int(strpos($settings[$key]->setting_rules, 'required')) ? '*' : '' }}</span></label>
                            <input type="text" name="settings[{{ $settings[$key]->setting_key }}]" class="form-control {{ $errors->has('settings.'.$settings[$key]->setting_key) ? 'is-invalid' : '' }}" value="{{ $settings[$key]->setting_value }}" placeholder="Masukkan {{ $settings[$key]->setting_name }}">
                            @if($errors->has('settings.'.$settings[$key]->setting_key))
                            <div class="form-control-feedback text-danger">{{ $errors->first('settings.'.$settings[$key]->setting_key) }}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="tile-footer"><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></div>
        </form>
      </div>
    </div>
  </div>
</main>

@endsection

@section('js-extra')

<script type="text/javascript">
    // Button Upload
    $(document).on("click", ".btn-upload", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        $("#"+id).trigger("click");
    });

    // File Change
    $(document).on("change", ".file", function(){
        readURL(this);
        $(this).val(null);
    });

    // Read URL
    function readURL(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            var id = $(input).attr("id");
            reader.onload = function(e){
                $(".src[data-id="+id+"]").val(e.target.result);
                $("img[data-id="+id+"]").attr("src",e.target.result).removeClass("d-none");
                $(".btn-preview[data-id="+id+"]").removeClass("d-none");
                $(".btn-delete[data-id="+id+"]").removeClass("d-none");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        $(".logo-text img").attr("src", "{{ asset('assets/images/logo/'.get_logo()) }}");
        $("img[data-id="+id+"]").attr("src","{{ asset('assets/images/logo/'.get_logo()) }}");
        $(".src[data-id="+id+"]").val($(".src[data-id="+id+"]").data("old"));
        $(".btn-preview[data-id="+id+"]").addClass("d-none");
        $(".btn-delete[data-id="+id+"]").addClass("d-none");
    });
</script>

@endsection