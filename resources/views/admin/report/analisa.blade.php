@extends('template/admin/main')

@section('title', 'Analisa')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-line-chart"></i> Analisa</h1>
      <p>Menu untuk menampilkan analisis data</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="#">Report</a></li>
      <li class="breadcrumb-item">Analisa</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-auto mx-auto">
        <div class="tile">
            <div class="tile-body">
                <form id="form-tanggal" class="form-inline" method="get" action="">
                    <input type="text" id="t1" class="form-control mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET['t1']) ? generate_date_slash($_GET['t1']) : '' }}" placeholder="Dari Tanggal">
                    <input type="text" id="t2" class="form-control mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET['t2']) ? generate_date_slash($_GET['t2']) : '' }}" placeholder="Sampai Tanggal">
                    <input type="hidden" name="t1" value="{{ isset($_GET['t1']) ? $_GET['t1'] : '' }}">
                    <input type="hidden" name="t2" value="{{ isset($_GET['t2']) ? $_GET['t2'] : '' }}">
                    <button type="submit" class="btn btn-primary btn-submit mb-2" {{ isset($_GET['t1']) && isset($_GET['t2']) ? '' : 'disabled' }}>Submit</button>
                </form>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
        <div class="tile">
            <h3 class="tile-title text-center">Usia</h3>
            <div class="embed-responsive embed-responsive-16by9">
                <canvas class="embed-responsive-item" id="pie-chart-usia"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="tile">
            <h3 class="tile-title text-center">Jenis Kelamin</h3>
            <div class="embed-responsive embed-responsive-16by9">
                <canvas class="embed-responsive-item" id="pie-chart-gender"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="tile">
            <h3 class="tile-title text-center">Frekuensi Kedatangan</h3>
            <div class="embed-responsive embed-responsive-16by9">
                <canvas class="embed-responsive-item" id="pie-chart-kedatangan"></canvas>
            </div>
        </div>
    </div>
  </div>
</main>

@endsection

@section('js-extra')

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
<script type="text/javascript">
    // Input Tanggal
    $(".input-tanggal").datepicker({
      	format: "dd/mm/yyyy",
      	autoclose: true,
      	todayHighlight: true
    });

    // Load Page
    $(window).on("load", function(){
		graph_usia();
        graph_gender();
        graph_kedatangan();
	});

    // Change Tanggal
    $(document).on("change", "#t1, #t2", function(){
        var t1 = $("#t1").val();
        var t2 = $("#t2").val();
        (t1 != '' && t2 != '') ? $("#form-tanggal .btn-submit").removeAttr("disabled") : $("#form-tanggal .btn-submit").attr("disabled","disabled");
    });

    // Button Submit
    $(document).on("click", "#form-tanggal .btn-submit", function(e){
        e.preventDefault();
        var t1 = $("#t1").val();
        var t2 = $("#t2").val();
        $("input[name=t1]").val(t1.split("/").join(""));
        $("input[name=t2]").val(t2.split("/").join(""));
        $("#form-tanggal").submit()
    });
	
	function graph_usia(){
		$.ajax({
			type: "get",
			url: "/admin/ajax/graph-usia",
			success: function(response){
				var result = JSON.parse(response);
				chart_pie("pie-chart-usia", result.label, result.data, result.bgcolor, "Usia ");
			}
		});
	}
	
	function graph_gender(){
		$.ajax({
			type: "get",
			url: "/admin/ajax/graph-gender",
			success: function(response){
				var result = JSON.parse(response);
				chart_pie("pie-chart-gender", result.label, result.data, result.bgcolor, "");
			}
		});
	}
	
	function graph_kedatangan(){
        var url = "{{ isset($_GET['t1']) && isset($_GET['t2']) ? htmlspecialchars_decode('/admin/ajax/graph-kedatangan?t1='.$_GET['t1'].'&t2='.$_GET['t2']) : '/admin/ajax/graph-kedatangan' }}";
        url = url.replace('&amp;','&');
		$.ajax({
			type: "get",
			url: url,
			success: function(response){
				var result = JSON.parse(response);
				chart_pie("pie-chart-kedatangan", result.label, result.data, result.bgcolor, "Kedatangan Pukul ");
			}
		});
	}
	
	function chart_pie(selector, labels, data, backgroundColors, prefixTooltip){
		var ctx = document.getElementById(selector);
		var myChart = new Chart(ctx, {
			type: "pie",
            data: {
				datasets: [{
					data: data,
					backgroundColor: backgroundColors,
					// label: 'Dataset 1'
				}],
				labels: labels
			},
			options: {
				responsive: true,
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return prefixTooltip + data['labels'][tooltipItem[0]['index']];
                        },
                        label: function(tooltipItem, data) {
                            return formatRibuan(data['datasets'][0]['data'][tooltipItem.index].toString(), "");
                        }
                    }
                }
			},
		});
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