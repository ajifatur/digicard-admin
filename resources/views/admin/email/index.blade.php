@extends('template/admin/main')

@section('title', 'Kelola Email')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-envelope"></i> Kelola Email</h1>
      <p>Menu untuk mengelola data email</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/email">Email</a></li>
      <li class="breadcrumb-item">Kelola Email</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">Kelola Email</h3>
          <div class="btn-group">
            <a class="btn btn-primary" href="/admin/email/create" title="Tulis Pesan"><i class="fa fa-lg fa-pencil"></i></a>
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
                        <th>Subjek</th>
                        <th width="150">Pengirim</th>
                        <th width="80">Waktu</th>
                        <th width="40">Hapus</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($email as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><a href="/admin/email/detail/{{ $data->id_email }}">{{ $data->subjek }}</a></td>
                                <td>
                                    {{ $data->nama_user }}
                                    <br>
                                    <small class="text-muted">{{ $data->email }}</small>
                                </td>
                                <td><span title="{{ $data->email_at }}" style="text-decoration: underline; cursor: help;">{{ date('Y-m-d', strtotime($data->email_at)) }}</span></td>
                                <td><a href="#" class="btn btn-danger btn-sm btn-block btn-delete" data-id="{{ $data->id_email }}" title="Hapus"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form id="form-delete" class="d-none" method="post" action="/admin/email/delete">
                {{ csrf_field() }}
                <input type="hidden" name="id">
            </form>
        </div>
      </div>
    </div>
  </div>
</main>

@endsection

@section('js-extra')

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
  // DataTable
  $('#table').DataTable();

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