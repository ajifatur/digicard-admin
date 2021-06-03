@extends('template/admin/main')

@section('title', 'Tulis Pesan')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-envelope"></i> Tulis Pesan</h1>
      <p>Menu untuk menulis pesan</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/email">Email</a></li>
      <li class="breadcrumb-item">Tulis Pesan</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <form id="form" method="post" action="/admin/email/store">
            {{ csrf_field() }}
            <div class="tile-title-w-btn">
                <h3 class="title">Tulis Pesan</h3>
                <p><button class="btn btn-primary btn-submit icon-btn" type="submit"><i class="fa fa-send mr-2"></i>Kirim</button></p>
            </div>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Subjek <span class="text-danger">*</span></label>
                        <input type="text" name="subjek" class="form-control {{ $errors->has('subjek') ? 'is-invalid' : '' }}" value="{{ old('subjek') }}" placeholder="Masukkan Subjek">
                        @if($errors->has('subjek'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('subjek')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label>Penerima <span class="text-danger">*</span></label>
                        <br>
                        <button type="button" id="btn-search" class="btn btn-warning mb-3 mr-2" data-toggle="modal" data-target="#modal-search"><i class="fa fa-search mr-2"></i>Cari</button>
                        <button type="button" id="btn-write" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-import"><i class="fa fa-download mr-2"></i>Import</button>
                        <input type="hidden" name="ids" id="id-penerima" class="form-control">
                        <input type="hidden" name="names" id="nama-penerima" value="">
                        <textarea id="email-penerima" name="emails" class="form-control" rows="2" readonly></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Konten <span class="text-danger">*</span></label>
                        <textarea name="konten" id="konten" class="d-none"></textarea>
                        <div id="editor"></div> 
                        @if($errors->has('konten'))
                        <small class="text-danger">{{ ucfirst($errors->first('konten')) }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tile-footer"><button class="btn btn-primary btn-submit icon-btn" type="submit"><i class="fa fa-send mr-2"></i>Kirim</button></div>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Modal Cari Penerima -->
<div class="modal fade" id="modal-search" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Pilih Penerima</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="col-12 pt-3" style="background-color: #e5e5e5;">
				<div class="">
					<div class="form-group col-md-12">
						@for($i=1; $i<=ceil(count($user)/100); $i++)
						<div class="form-check form-check-inline">
							<input class="form-check-input checkbox-batch" name="batch" type="radio" id="checkbox-{{ $i }}" value="{{ $i }}">
							<label class="form-check-label" for="checkbox-{{ $i }}">{{ (($i - 1) * 100) + 1 }}-{{ $i * 100 }}</label>
						</div>
						@endfor
					</div>
				</div>
			</div>
			<div class="modal-body">
				<table class="table mb-0" id="table-receivers">
					@foreach($user as $data)
					<tr class="tr-checkbox" data-id="{{ $data->id_user }}" data-email="{{ $data->email }}">
						<td>
							<input name="receivers[]" class="input-receivers d-none" type="checkbox" data-id="{{ $data->id_user }}" data-email="{{ $data->email }}" value="{{ $data->id_user }}">
							<span class="text-primary">{{ $data->email }}</span><br><span class="text-dark">{{ $data->nama_user }}</span>
						</td>
						<td width="30" align="center" class="td-check align-middle">
							<i class="fa fa-check text-primary d-none"></i>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="temp-id">
				<span><strong id="count-checked">0</strong> email terpilih.</span>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="btn-choose">Pilih</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Import Email</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
            	<form id="form-import" method="post" action="#" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="file" name="file" id="file" class="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					<button class="btn btn-sm btn-primary btn-file d-none"><i class="fa fa-folder-open mr-2"></i>Pilih File...</button>
					<br>
					<input type="hidden" name="import" id="src-file">
					<div class="small mt-2 text-muted">Hanya mendukung format: .XLS, .XLSX, dan .CSV</div>
				</form>
			</div>
			<div class="modal-footer d-none">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="btn-import">Import</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-extra')

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script type="text/javascript">		
    // Button File
    $(document).on("click", ".btn-file", function(e){
		e.preventDefault();
        $("#file").trigger("click");
    });
	
    // Change Input File
    $(document).on("change", "#file", function(){
        //readURL(this);
		$("#modal-import .modal-footer").removeClass("d-none");
    });

    function readURL(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e){
				$("#modal-import .modal-footer").removeClass("d-none");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
	
	// Import Email
	$(document).on("click", "#btn-import", function(e){
		e.preventDefault();
		var file_data = $('#file')[0].files[0];
		var formData = new FormData();                  
		formData.append("file", file_data);           
		formData.append("_token", $("input[name=_token]").val());
		$.ajax({
			type: "post",
			url: "/admin/email/import",
			cache: false,
			processData: false,
			contentType: false,
			data: formData,
			success: function(response){
				var result = JSON.parse(response);
				var arrayName = [];
				var arrayEmail = [];
				var i;
				for(i = 0; i < result[0].length; i++){
					arrayName.push(result[0][i][1]);
					arrayEmail.push(result[0][i][2]);
				}
				var names = arrayName.join(", ");
				var emails = arrayEmail.join(", ");
				$("#nama-penerima").val(names);
				$("#email-penerima").val(emails);
				$("#modal-import").modal("toggle");
			}
		});
	});
	
	// "Cari Penerima" Click
	$(document).on("click", "#btn-search", function(e){
		e.preventDefault();
		var ids = $("#id-penerima").val();
		var arrayId = ids.length > 0 ? ids.split(",") : [];
		arrayId.forEach(function(item){
			$(".input-receivers[data-id="+item+"]").attr("checked", "checked");
			actionChecked($(".input-receivers[data-id="+item+"]"), true);
		});
		countChecked(arrayId);
	});
	
	// Checkbox Batch
	$(document).on("change", ".checkbox-batch", function(){
		var value = $(".checkbox-batch:checked").val();
		var checkeds = $(".input-receivers");
		checkeds.each(function(key,elem){
			key >= ((value-1)*100) && key < (value*100) ? $(elem).prop("checked") ? $(elem).prop("checked", true) : $(elem).prop("checked", true) : $(elem).prop("checked", false);
			key >= ((value-1)*100) && key < (value*100) ? $(elem).prop("checked") ? actionChecked($(elem), true) : actionChecked($(elem), true) : actionChecked($(elem), false);
		});
		countChecked($(".input-receivers:checked"));
	});
	
	// Table Receivers Click
	$(document).on("click", ".tr-checkbox", function(e){
		e.preventDefault();
		var id = $(this).data("id");
		var email = $(this).data("email");
		var max = 100;
		$(".checkbox-batch").each(function(key,elem){
			$(elem).prop("checked",false);
		});
		if($(".input-receivers[data-id="+id+"]").prop("checked")){
			$(".input-receivers[data-id="+id+"]").prop("checked", false);
			actionChecked($(".input-receivers[data-id="+id+"]"), false);
			countChecked($(".input-receivers:checked"));
		}
		else{
			$(".input-receivers[data-id="+id+"]").prop("checked", true);
			actionChecked($(".input-receivers[data-id="+id+"]"), true);
			var count = countChecked($(".input-receivers:checked"));
			if(count > max){
				alert("Maksimal email yang bisa dipilih adalah "+max);
				$(".input-receivers[data-id="+id+"]").prop("checked", false);
				actionChecked($(".input-receivers[data-id="+id+"]"), false);
				countChecked($(".input-receivers:checked"));
			}
		}
	});
	
	// "Pilih" Click
	$(document).on("click", "#btn-choose", function(e){
		e.preventDefault();
		var arrayId = [];
		var arrayEmail = [];
		$(".input-receivers:checked").each(function(){
			arrayId.push($(this).val());
			arrayEmail.push($(this).data("email"));
		});
		var joinId = arrayId.length > 0 ? arrayId.join(",") : '';
		$("#id-penerima").val(joinId);
		var joinEmail = arrayEmail.length > 0 ? arrayEmail.join(", ") : '';
		$("#email-penerima").text(joinEmail);
		$(".modal").modal("hide");
	});

    // Close Modal
    $('.modal').on('hidden.bs.modal', function(e){
		$(".checkbox-batch:checked").removeAttr("checked");
		$(".input-receivers:checked").each(function(){
			$(this).removeAttr("checked");
			actionChecked($(this), false);
		});
		//$("#search").val(null);
    });
	
	function actionChecked(elem, is_checked){
		if(is_checked == true){
			$(elem).parents(".tr-checkbox").addClass("tr-active");
			$(elem).parents(".tr-checkbox").find(".td-check .fa").removeClass("d-none");
		}
		else{
			$(elem).parents(".tr-checkbox").removeClass("tr-active");
			$(elem).parents(".tr-checkbox").find(".td-check .fa").addClass("d-none");
		}
	}
	
	function countChecked(array){
		var checked = array.length;
		$("#count-checked").text(checked);
		checked <= 0 ? $("#btn-choose").attr("disabled","disabled") : $("#btn-choose").removeAttr("disabled");
		return checked;
	}
	
	function saveToStorage(id, email){
		if($.inArray(id, tempId) < 0) tempId.push(id);
		if($.inArray(email, storageEmail) < 0) storageEmail.push(email);
	}
	
	function removeFromStorage(id, email){
		tempId.forEach(function(item,key){
			if(item == id) tempId.splice(key,1);
		});
		storageEmail.forEach(function(item,key){
			if(item == email) storageEmail.splice(key,1);
		});
	}
	
    // Quill Editor
    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        ['bold', 'italic', 'underline', 'strike', 'link', 'image'],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'align': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'color': [] }, { 'background': [] }],
        ['clean']     
    ];

    var quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Tulis sesuatu...',
        theme: 'snow',
        imageResize: {
            displayStyles: {
                backgroundColor: 'black',
                border: 'none',
                color: 'white'
            },
            modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
        }
    });

    // Button Submit
    $(document).on("click", ".btn-submit", function(e){
        var myEditor = document.querySelector('#editor');
        var html = myEditor.children[0].innerHTML;
        $("#konten").text(html);
        $("#form").submit();
    });
</script>

@endsection

@section('css-extra')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style type="text/css">
    #editor {height: 300px;}
	.modal-content {max-height: 500px; overflow-y: hidden;}
	.modal-body {overflow-y: auto;}
	#table-receivers tr td {padding: .5rem!important;}
	#table-receivers tr:hover {background-color: #eeeeee!important;}
	.tr-checkbox {cursor: pointer;}
	.tr-active {background-color: #e5e5e5!important;}
	.ql-editor h1, .ql-editor h2, .ql-editor h3, .ql-editor h4, .ql-editor h5, .ql-editor h6, .ql-editor p {margin-bottom: .5rem!important;}
	.ql-editor ol li, .ql-editor ul li {margin-bottom: 0!important;}
</style>

@endsection