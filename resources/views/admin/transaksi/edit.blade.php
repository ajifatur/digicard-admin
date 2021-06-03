@extends('template/admin/main')

@section('title', 'Edit Transaksi')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-money"></i> Edit Transaksi</h1>
      <p>Menu untuk mengedit data transaksi</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/transaksi">Transaksi</a></li>
      <li class="breadcrumb-item">Edit Transaksi</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <form method="post" action="/admin/transaksi/update">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $transaksi->id_transaksi }}">
            <div class="tile-title-w-btn">
                <h3 class="title">Edit Transaksi</h3>
                <p><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></p>
            </div>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nama <span class="text-danger">*</span></label>
                        <select name="nama" id="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}">
                            <option value="" disabled>--Pilih Nama--</option>
                            @foreach($user as $data)
                            <option value="{{ $data->id_user }}" {{ $transaksi->id_user == $data->id_user ? 'selected' : '' }}>{{ $data->username }} - {{ $data->nama_user }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('nama'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('nama')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Transaksi <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="jenis-transaksi-1" type="radio" name="jenis_transaksi" value="1" {{ $transaksi->jenis_transaksi == 1 ? 'checked' : '' }}>Top Up
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="jenis-transaksi-2" type="radio" name="jenis_transaksi" value="2" {{ $transaksi->jenis_transaksi == 2 ? 'checked' : '' }}>TRX
                            </label>
                        </div>
                        @if($errors->has('jenis_transaksi'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('jenis_transaksi')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="tanggal_transaksi" class="form-control {{ $errors->has('tanggal_transaksi') ? 'is-invalid' : '' }}" value="{{ generate_date_format(date('Y-m-d', strtotime($transaksi->waktu_transaksi)), 'd/m/y') }}" placeholder="Masukkan Tanggal Transaksi">
                        <small class="form-text text-muted">Format: dd/mm/yyyy</small>
                        @if($errors->has('tanggal_transaksi'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('tanggal_transaksi')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jam Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="jam_transaksi" class="form-control {{ $errors->has('jam_transaksi') ? 'is-invalid' : '' }}" value="{{ date('H:i', strtotime($transaksi->waktu_transaksi)) }}" placeholder="Masukkan Jam Transaksi">
                        <small class="form-text text-muted">Format: jj:mm</small>
                        @if($errors->has('jam_transaksi'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('jam_transaksi')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nominal Transaksi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text {{ $errors->has('nominal_transaksi') ? 'border-danger' : '' }}">Rp.</span></div>
                            <input type="text" name="nominal_transaksi" class="form-control number-only thousand-format {{ $errors->has('nominal_transaksi') ? 'is-invalid' : '' }}" value="{{ number_format($transaksi->nominal_transaksi,0,'.','.') }}" placeholder="Masukkan Nominal Transaksi">
                        </div>
                        @if($errors->has('nominal_transaksi'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('nominal_transaksi')) }}</div>
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

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.js') }}"></script></script>
<script type="text/javascript">
    // Input Nama
    $("#nama").select2();

    // Input Tanggal Transaksi
    $("input[name=tanggal_transaksi]").datepicker({
      	format: "dd/mm/yyyy",
      	autoclose: true,
      	todayHighlight: true
    });

    // Input Jam Transaksi
    $("input[name=jam_transaksi]").clockpicker({
        autoclose: true
    });

    // Input Hanya Nomor
    $(document).on("keypress", ".number-only", function(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode >= 48 && charCode <= 57) { 
            // 0-9 only
            return true;
        }
        else{
            return false;
        }
    });

    // Input Format Ribuan
    $(document).on("keyup", ".thousand-format", function(){
        var value = $(this).val();
        $(this).val(formatRibuan(value, ""));
    });

    // Function Format Ribuan
    function formatRibuan(angka, prefix){
        var number_string = angka.replace(/\D/g,'');
        number_string = (number_string.length > 1) ? number_string.replace(/^(0+)/g, '') : number_string;
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);
     
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
     
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css') }}">
<style type="text/css">
    @if($errors->has('nama')) .select2-container--default .select2-selection--single {border-color: #dc3545!important;} @endif
</style>

@endsection