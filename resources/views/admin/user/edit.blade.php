@extends('template/admin/main')

@section('title', 'Edit User')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-user"></i> Edit User</h1>
      <p>Menu untuk mengedit data user</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/user">User</a></li>
      <li class="breadcrumb-item">Edit User</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <form method="post" action="/admin/user/update">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $user->id_user }}">
            <div class="tile-title-w-btn">
                <h3 class="title">Edit User</h3>
                <p><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></p>
            </div>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" value="{{ $user->nama_user }}" placeholder="Masukkan Nama">
                        @if($errors->has('nama'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('nama')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tag <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ $user->username }}" placeholder="Masukkan Tag">
                        @if($errors->has('username'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $user->email }}" placeholder="Masukkan Email">
                        @if($errors->has('email'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('email')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_hp" class="form-control {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}" value="{{ $user->nomor_hp }}" placeholder="Masukkan Nomor HP">
                        @if($errors->has('nomor_hp'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('nomor_hp')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tanggal_lahir" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" value="{{ $user->tanggal_lahir != '' ? generate_date_format($user->tanggal_lahir, 'd/m/y') : '' }}" placeholder="Masukkan Tanggal Lahir">
                        <small class="form-text text-muted">Format: dd/mm/yyyy</small>
                        @if($errors->has('tanggal_lahir'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('tanggal_lahir')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="jenis-kelamin-1" type="radio" name="jenis_kelamin" value="L" {{ $user->jenis_kelamin == 'L' ? 'checked' : '' }}>Laki-Laki
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="jenis-kelamin-2" type="radio" name="jenis_kelamin" value="P" {{ $user->jenis_kelamin == 'P' ? 'checked' : '' }}>Perempuan
                            </label>
                        </div>
                        @if($errors->has('jenis_kelamin'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('jenis_kelamin')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control {{ $errors->has('pekerjaan') ? 'is-invalid' : '' }}" value="{{ $user->pekerjaan }}" placeholder="Masukkan Pekerjaan">
                        @if($errors->has('pekerjaan'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('pekerjaan')) }}</div>
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

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    // Input Tanggal Lahir
    $("input[name=tanggal_lahir]").datepicker({
      	format: "dd/mm/yyyy",
      	autoclose: true,
      	todayHighlight: true
    });
</script>

@endsection