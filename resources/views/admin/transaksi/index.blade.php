@extends('template/admin/main')

@section('title', 'Kelola Transaksi')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-money"></i> Kelola Transaksi</h1>
      <p>Menu untuk mengelola data transaksi</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/transaksi">Transaksi</a></li>
      <li class="breadcrumb-item">Kelola Transaksi</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-auto mx-auto">
        <div class="tile">
            <div class="tile-body">
                <form id="form-filter" class="form-inline" method="get" action="">
					<select name="transaksi" class="form-control mr-2">
					  <option value="1" {{ isset($_GET['transaksi']) ? $_GET['transaksi'] == 1 ? 'selected' : '' : '' }}>Top Up</option>
					  <option value="2" {{ isset($_GET['transaksi']) ? $_GET['transaksi'] == 2 ? 'selected' : '' : '' }}>TRX</option>
					</select>
					<select name="bulan" class="form-control mr-2">
					  @foreach($bulan as $key=>$data)
					  <option value="{{ ($key+1) }}" {{ isset($_GET['bulan']) ? $_GET['bulan'] == ($key+1) ? 'selected' : '' : '' }} {{ !isset($_GET['bulan']) ? date('n') == ($key+1) ? 'selected' : '' : '' }}>{{ $data[0] }}</option>
					  @endforeach
					</select>
					<select name="tahun" class="form-control mr-2">
					  @for($y=date('Y'); $y>=2018; $y--)
					  <option value="{{ $y }}" {{ isset($_GET['tahun']) ? $_GET['tahun'] == $y ? 'selected' : '' : '' }}>{{ $y }}</option>
					  @endfor
					</select>
                    <button type="submit" class="btn btn-primary btn-submit-filter">Submit</button>
                </form>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">Kelola Transaksi</h3>
          <div class="btn-group">
            <a class="btn btn-primary" href="/admin/transaksi/create" title="Tambah Transaksi"><i class="fa fa-lg fa-plus"></i></a>
            <a class="btn btn-primary" href="{{ isset($_GET['bulan']) && isset($_GET['tahun']) ? '/admin/transaksi/export?bulan='.$_GET['bulan'].'&tahun='.$_GET['tahun'] : '/admin/transaksi/export' }}" title="Ekspor Data Transaksi"><i class="fa fa-lg fa-file-excel-o"></i></a>
            <a class="btn btn-primary btn-import" href="#" title="Import Data Transaksi"><i class="fa fa-lg fa-download"></i></a>
          </div>
        </div>
        <div class="tile-body">
            @if(Session::get('message') != null)
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th width="80">Waktu</th>
                        <th>Nama</th>
                        <th width="80">Jenis</th>
                        <th width="80">Nominal</th>
                        <th width="40">Edit</th>
                        <th width="40">Hapus</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($transaksi as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->waktu_transaksi }}</td>
                                <td>
                                    {{ $data->nama_user }}
                                    <br>
                                    <small class="text-muted">Tag: {{ $data->username }}</small>
                                </td>
                                <td>{{ $data->jenis_transaksi == 1 ? 'Top Up' : 'TRX' }}</td>
                                <td>{{ number_format($data->nominal_transaksi,0,'.','.') }}</td>
                                <td><a href="/admin/transaksi/edit/{{ $data->id_transaksi }}" class="btn btn-warning btn-sm btn-block" data-id="{{ $data->id_transaksi }}" title="Edit"><i class="fa fa-edit"></i></a></td>
                                <td><a href="#" class="btn btn-danger btn-sm btn-block btn-delete" data-id="{{ $data->id_transaksi }}" title="Hapus"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form id="form-delete" class="d-none" method="post" action="/admin/transaksi/delete">
                {{ csrf_field() }}
                <input type="hidden" name="id">
            </form>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="modal" id="modal-import">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="post" action="/admin/transaksi/import" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Import Data Transaksi</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
					{{ csrf_field() }}
          <p>
            Tata cara mengimport data transaksi:
            <ol>
              <li>Export terlebih dahulu data transaksi <strong><a href="/admin/transaksi/export">Disini</a></strong>.</li>
              <li>Jika ingin menambah transaksi baru, tambahkan data di bawah baris data terakhir dari file yang sudah di-export tadi.</li>
              <li>Pastikan semua kolom tidak boleh kosong.</li>
              <li>Import data dari file excel yang sudah di-update tadi.</li>
            </ol>
          </p>
					<input type="file" name="file" id="file" class="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					<button class="btn btn-sm btn-primary btn-file d-none"><i class="fa fa-folder-open mr-2"></i>Pilih File...</button>
					<div class="small mt-2 text-muted">Hanya mendukung format: .XLS, .XLSX, dan .CSV</div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary btn-submit-import" type="submit" disabled>Import</button>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('js-extra')

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
  // DataTable
  $('#table').DataTable();

  // Button Import
  $(document).on("click", ".btn-import", function(e){
    e.preventDefault();
    $("#modal-import").modal("show");
  });	

  // Button Pilih File
  $(document).on("click", ".btn-file", function(e){
    e.preventDefault();
    $("#file").trigger("click");
  });

  // Change Input File
  $(document).on("change", "#file", function(){
    $(".btn-submit-import").removeAttr("disabled");
  });

  // Tutup Modal Import
  $("#modal-import").on("hidden.bs.modal", function(e){
    $("#file").val(null);
    $(".btn-submit-import").attr("disabled","disabled");
  });

  // Button Delete
  $(document).on("click", ".btn-delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var ask = confirm("Anda yakin ingin menghapus data ini?");
      if(ask){
          $("#form-delete input[name=id]").val(id);
          $("#form-delete").submit();
      }
  });
</script>

@endsection