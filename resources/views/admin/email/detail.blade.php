@extends('template/admin/main')

@section('title', 'Detail Pesan')

@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-envelope"></i> Detail Pesan</h1>
      <p>Menu untuk menampilkan detail pesan</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="/admin/email">Email</a></li>
      <li class="breadcrumb-item">Detail Pesan</li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title">Detail Pesan</h3>
            </div>
            <div class="tile-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Subjek</label>
                        <input type="text" name="subjek" class="form-control {{ $errors->has('subjek') ? 'is-invalid' : '' }}" value="{{ $email->subjek }}" placeholder="Masukkan Subjek" readonly>
                        @if($errors->has('subjek'))
                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('subjek')) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label>Penerima</label>
                        <br>
                        <button type="button" id="btn-search" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-search"><i class="fa fa-search mr-2"></i>Lihat Penerima</button>
                        <input type="hidden" name="ids" id="id-penerima" class="form-control" value="{{ $email->id_penerima }}">
                        <textarea id="email-penerima" name="emails" class="form-control" rows="2" readonly>{{ $email->email_penerima }}</textarea>
                        @php
                            $emails = explode(', ', $email->email_penerima);
                        @endphp
                    </div>
                    <div class="form-group col-md-12">
                        <label>Konten</label>
                        <div class="ql-snow"><div class="ql-editor">{!! html_entity_decode($email->konten) !!}</div></div> 
                    </div>
                </div>
            </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Cari Penerima -->
<div class="modal fade" id="modal-search" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Lihat Penerima</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
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
							<i class="fa fa-check text-primary {{ in_array($data->email, $emails) ? '' : 'd-none' }}"></i>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="temp-id">
				<span><strong id="count-checked">{{ count($emails) }}</strong> email terpilih.</span>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-extra')

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

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
    .ql-snow {border: 1px solid #bebebe;}
	.ql-editor h1, .ql-editor h2, .ql-editor h3, .ql-editor h4, .ql-editor h5, .ql-editor h6, .ql-editor p {margin-bottom: .5rem!important;}
	.ql-editor ol li, .ql-editor ul li {margin-bottom: 0!important;}
</style>

@endsection