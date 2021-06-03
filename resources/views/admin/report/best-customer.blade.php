@extends('template/admin/main')

@section('title', 'Best Customer')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-line-chart"></i> Best Customer</h1>
      <p>Menu untuk menampilkan data customer dengan transaksi lebih dari Rp 75.000 per bulan</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/transaksi">Report</a></li>
      <li class="breadcrumb-item">Best Customer</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">Best Customer</h3>
          <div class="btn-group">
			  <select id="bulan" class="form-control mr-2">
				  @foreach($bulan as $key=>$data)
				  <option value="{{ ($key+1) }}" {{ isset($_GET['bulan']) ? $_GET['bulan'] == ($key+1) ? 'selected' : '' : '' }} {{ !isset($_GET['bulan']) ? date('n') == ($key+1) ? 'selected' : '' : '' }}>{{ $data[0] }}</option>
				  @endforeach
			  </select>
			  <select id="tahun" class="form-control">
				  @for($y=date('Y'); $y>=2018; $y--)
				  <option value="{{ $y }}" {{ isset($_GET['tahun']) ? $_GET['tahun'] == $y ? 'selected' : '' : '' }}>{{ $y }}</option>
				  @endfor
			  </select>
          </div>
        </div>
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="table">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th>Nama</th>
                        <th width="100">Belanja</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($customer as $data)
						<tr>
							<td>{{ $i }}</td>
							<td>
								{{ $data->nama_user }}
								<br>
								<small class="text-muted">Tag: {{ $data->username }}</small>
							</td>
							<td>{{ number_format($data->belanja,0,'.','.') }}</td>
						</tr>
						@php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
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
  $('#table').DataTable({
  	"order": [2, 'desc']
  });

  // Change Bulan dan Tahun
  $(document).on("change", "#bulan, #tahun", function(){
    var bulan = $("#bulan").val();
    var tahun = $("#tahun").val();
	window.location.href = '/admin/report/best-customer?bulan='+bulan+'&tahun='+tahun;
  });
</script>

@endsection