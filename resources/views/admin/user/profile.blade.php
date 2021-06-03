@extends('template/admin/main')

@section('title', 'Profil')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-user"></i> Profil</h1>
      <p>Menu untuk menampilkan profil</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/profil">Profil</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <form method="post" action="/admin/profil/update">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $admin->id_user }}">
            <div class="tile-title-w-btn">
                <h3 class="title">Profil</h3>
                <p><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></p>
            </div>
            <div class="tile-body">
                @if(Session::get('message') != null)
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                </div>
                @endif
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" value="{{ $admin->nama_user }}" placeholder="Masukkan Nama">
                        @if($errors->has('nama'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('nama')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ $admin->username }}" placeholder="Masukkan Tag">
                        @if($errors->has('username'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $admin->email }}" placeholder="Masukkan Email">
                        @if($errors->has('email'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('email')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <div class="input-group-append"><button class="btn btn-toggle-password input-group-text {{ $errors->has('password') ? 'border-danger' : '' }}"><i class="fa fa-eye"></i></button></div>
                        </div>
                        <small class="text-muted">Kosongi saja jika tidak ingin mengganti password.</small>
                        @if($errors->has('password'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('password')) }}</div>
                        @endif
                    </div>
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
    // Button Toggle Password
    $(document).on("click", ".btn-toggle-password", function(e){
        e.preventDefault();
        if($(this).find("i").hasClass("fa-eye")){
            $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
            $("input[name=password]").attr("type","text");
        }
        else{
            $(this).find("i").addClass("fa-eye").removeClass("fa-eye-slash");
            $("input[name=password]").attr("type","password");
        }
    });
</script>

@endsection