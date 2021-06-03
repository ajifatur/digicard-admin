@extends('template/admin/main')

@section('title', 'Dashboard')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
      <p>Menu untuk menampilkan data dan statistik penting</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-6">
      <div class="widget-small info coloured-icon"><i class="icon fa fa-arrow-down fa-3x"></i>
        <div class="info">
          <h4>Top Up</h4>
          <p><b>Rp {{ number_format($top_up,0,'.','.') }}</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-6">
      <div class="widget-small danger coloured-icon"><i class="icon fa fa-arrow-up fa-3x"></i>
        <div class="info">
          <h4>TRX</h4>
          <p><b>Rp {{ number_format($trx,0,'.','.') }}</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-6">
      <div class="widget-small warning coloured-icon"><i class="icon fa fa-money fa-3x"></i>
        <div class="info">
          <h4>Saldo</h4>
          <p><b>Rp {{ number_format($saldo,0,'.','.') }}</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-6">
      <div class="widget-small primary coloured-icon"><i class="icon fa fa-user fa-3x"></i>
        <div class="info">
          <h4>Member</h4>
          <!--<p><b>{{ number_format($member,0,'.','.') }}</b></p>-->
          <p><b>{{ number_format(17245,0,'.','.') }}</b></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-12">
          <div class="tile">
            <div class="tile-title-w-btn">
              <h3 class="title">Transaksi</h3>
              <div class="btn-group">
                <select id="bulan-1" class="form-control mr-2">
                  @foreach($bulan as $key=>$data)
                  <option value="{{ ($key+1) }}">{{ $data[0] }}</option>
                  @endforeach
                </select>
                <select id="tahun-1" class="form-control">
                  @for($y=date('Y'); $y>=2018; $y--)
                  <option value="{{ $y }}" {{ $y == 2018 ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="bar-chart-transaksi"></canvas>
            </div>
          </div>
        </div>
		  <!--
        <div class="col-lg-12">
          <div class="tile">
            <div class="tile-title-w-btn">
              <h3 class="title">Frekuensi Kunjungan</h3>
              <div class="btn-group">
                <select id="bulan-2" class="form-control mr-2">
                  @foreach($bulan as $key=>$data)
                  <option value="{{ ($key+1) }}">{{ $data[0] }}</option>
                  @endforeach
                </select>
                <select id="tahun-2" class="form-control">
                  @for($y=date('Y'); $y>=2018; $y--)
                  <option value="{{ $y }}" {{ $y == 2018 ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="bar-chart-kunjungan"></canvas>
            </div>
          </div>
        </div>
		  -->
      </div>
    </div>
  </div>
</main>

@endsection

@section('js-extra')

<!-- <script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/chart.js') }}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
<script type="text/javascript">
  var chartTransaksi;
  var chartKunjungan;

  $(window).on("load", function(){
		graph_transaksi(1, 2018);
		//graph_kunjungan(1, 2018);
	});

  // Change Bulan dan Tahun (Grafik Transaksi)
  $(document).on("change", "#bulan-1, #tahun-1", function(){
    var bulan = $("#bulan-1").val();
    var tahun = $("#tahun-1").val();
		graph_transaksi(bulan, tahun);
  });

  // Change Bulan dan Tahun (Grafik Kunjungan)
  $(document).on("change", "#bulan-2, #tahun-2", function(){
    var bulan = $("#bulan-2").val();
    var tahun = $("#tahun-2").val();
    graph_kunjungan(bulan, tahun);
  });
	
	function graph_transaksi(bulan, tahun){
		$.ajax({
			type: "get",
			url: "/admin/ajax/graph-transaksi?bulan="+bulan+"&tahun="+tahun,
			success: function(response){
        var result = JSON.parse(response);
        if(chartTransaksi != null || chartTransaksi != undefined) chartTransaksi.destroy();
				chartTransaksi = chart_bar("bar-chart-transaksi", result.labels, Object.assign(result.datasets), result.bulan + " " + result.tahun, "Nominal (Rp)", true);
			}
		});
	}
	
	function graph_kunjungan(bulan, tahun){
		$.ajax({
			type: "get",
			url: "/admin/ajax/graph-kunjungan?bulan="+bulan+"&tahun="+tahun,
			success: function(response){
				var result = JSON.parse(response);
        if(chartKunjungan != null || chartKunjungan != undefined) chartKunjungan.destroy();
				chartKunjungan = chart_bar("bar-chart-kunjungan", result.labels, Object.assign(result.datasets), result.bulan + " " + result.tahun, "Kunjungan");
			}
		});
	}
	
	function chart_bar(selector, labels, datasets, xLabel, yLabel, moneyFormat = false){
		var ctx = document.getElementById(selector);
		var myChart = new Chart(ctx, {
			type: "bar",
			data: {
				labels: labels,
				datasets: datasets,
			},
			options: {
				responsive: true,
				scales: {
					yAxes: [{
            scaleLabel: {
              display: true,
              labelString: yLabel
            },
						ticks: {
							beginAtZero: true,
              callback: function(value, index, values) {
                return moneyFormat == false ? Math.floor(value) === value ? value : '' : formatRibuan(value.toString(), "");
              }
							//stepSize: 2
						}
					}],
          xAxes: [{
            scaleLabel: {
              display: true,
              labelString: xLabel
            },
          }]
				},
        tooltips: {
          callbacks: {
            title: function(tooltipItem, data) {
              return tooltipItem[0].label + " " + xLabel;
            },
            label: function(tooltipItem, data) {
              return moneyFormat == true ? "Rp " + formatRibuan(tooltipItem.yLabel.toString(), "") : tooltipItem.yLabel.toString();
            }
          }
        }
			}
    });
    return myChart;
	}

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

@endsection