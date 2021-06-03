@extends('template/admin/main')

@section('title', 'Kelola User')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-user"></i> Kelola User</h1>
      <p>Menu untuk mengelola data user</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/user">User</a></li>
      <li class="breadcrumb-item">Kelola User</li>
    </ul>
  </div>
  @if(count($birthday_users)>0)
  <div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning shadow">
            <a href="#birthday-users">{{ count($birthday_users) }} orang berulang tahun hari ini.</a>
        </div>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">Kelola User</h3>
          <div class="btn-group">
            <a class="btn btn-primary" href="/admin/user/create" title="Tambah User"><i class="fa fa-lg fa-plus"></i></a>
            <a class="btn btn-primary" href="/admin/user/export" title="Export Data User"><i class="fa fa-lg fa-file-excel-o"></i></a>
            <a class="btn btn-primary btn-import" href="#" title="Import Data User"><i class="fa fa-lg fa-download"></i></a>
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
                        <th>Nama</th>
                        <th width="100">Tag</th>
                        <th width="100">Nomor HP</th>
                        <th width="100">Pekerjaan</th>
                        <th width="40">Edit</th>
                        <th width="40">Hapus</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                  {{ $user->nama_user }}
                                  <br>
                                  <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->nomor_hp }}</td>
                                <td>{{ $user->nama_pekerjaan }}</td>
                                <td><a href="/admin/user/edit/{{ $user->id_user }}" class="btn btn-warning btn-sm btn-block" data-id="{{ $user->id_user }}" title="Edit"><i class="fa fa-edit"></i></a></td>
                                <td><a href="#" class="btn btn-danger btn-sm btn-block {{ $user->id_user > 1 ? 'btn-delete' : '' }}" data-id="{{ $user->id_user }}" style="{{ $user->id_user > 1 ? '' : 'cursor: not-allowed' }}" title="{{ $user->id_user <= 1 ? $user->id_user == Auth::user()->id_user ? 'Tidak dapat menghapus akun sendiri' : 'Akun ini tidak boleh dihapus' : 'Hapus' }}"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form id="form-delete" class="d-none" method="post" action="/admin/user/delete">
                {{ csrf_field() }}
                <input type="hidden" name="id">
            </form>
        </div>
      </div>
    </div>
    @if(count($birthday_users)>0)
    <div class="col-md-12">
    <a id="birthday-users">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">User Berulang Tahun</h3>
        </div>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table-2">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th>Nama</th>
                        <th width="100">Tag</th>
                        <th width="100">Email</th>
                        <th width="40">Ucapkan</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($birthday_users as $user)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $user->nama_user }}
                                    <br>
                                    <small class="text-muted">{{ generate_age($user->tanggal_lahir) }} tahun</small>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td><a href="#" class="btn btn-success btn-sm btn-block" data-id="{{ $user->id_user }}" title="Kirim Ucapan"><i class="fa fa-gift"></i></a></td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </a>
    </div>
    @endif
  </div>
</main>

<div class="modal" id="modal-import">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="post" action="/admin/user/import" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Import Data User</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
					{{ csrf_field() }}
          <p>
            Tata cara mengimport data user:
            <ol>
              <li>Export terlebih dahulu data user <strong><a href="/admin/user/export">Disini</a></strong>.</li>
              <li>Jika ingin menambah user baru, tambahkan data di bawah baris data terakhir dari file yang sudah di-export tadi.</li>
              <li>Pastikan tag dan username <strong>tidak boleh sama dari setiap user</strong> dan <strong>tidak boleh kosong</strong>.</li>
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
  @if(count($birthday_users)>0) $('#table-2').DataTable(); @endif

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