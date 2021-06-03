<!DOCTYPE html>
<html dir="ltr">

<head>
    <title>Recovery Password | {{ get_website_name() }} &#8211; {{ get_tagline() }}</title>
    @include('template/admin/_head')
    <style type="text/css">
        .auth-wrapper {height: calc(100vh)!important; background-color: {{ get_warna_primer() }}!important;}
        .auth-box {background-color: {{ get_warna_tersier() }}!important; border-color: {{ get_warna_sekunder() }}!important; margin: auto!important;}
        .input-group>.input-group-append:not(:last-child)>.input-group-text {border-top-right-radius: 2px; border-bottom-right-radius: 2px;}
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
            <div class="auth-box border-top border-secondary">
                <div id="loginform">
                    <div class="text-center">
						@if(Session::get('message'))
						<div class="alert alert-success">{{ Session::get('message') }}</div>
						@endif
                        <span class="text-white">Masukkan email Anda di bawah ini dan Kami akan mengirimi Anda instruksi untuk mengembalikan password Anda.</span>
                    </div>
                    <div class="row m-t-20">
                        <!-- Form -->
                        <form class="col-12" method="post" action="/recovery-password">
							{{ csrf_field() }}
							<input type="hidden" name="referral" value="{{ Session::get('ref') }}">
                            <!-- email -->
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                                </div>
                                <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}" placeholder="Alamat Email" aria-label="Username" aria-describedby="basic-addon1">
								@if($errors->has('email'))
								<small class="form-row col-12 mt-1 text-danger">{{ ucfirst($errors->first('email')) }}</small>
								@endif
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-20 p-t-20 border-top border-secondary">
                                <div class="col-12">
                                    <a class="btn btn-warning" href="/login">Kembali ke Login</a>
                                    <button class="btn btn-success float-right" type="submit">Recover</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('templates/matrix-admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('templates/matrix-admin/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('templates/matrix-admin/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $(".preloader").fadeOut();
    </script>

</body>

</html>