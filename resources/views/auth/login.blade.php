<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-alpha.22
* @link https://tabler.io
* Copyright 2018-2021 The Tabler Authors
* Copyright 2018-2021 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Sign in.</title>
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/libs/sweetalert/sweetalert.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet"/>
  </head>
  <script type="text/javascript">
    let BASE_URL = '{{ url('/') }}'
  </script>
  <body class="antialiased border-primary d-flex flex-column">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="flex-fill d-flex flex-column justify-content-center py-4">
      <div class="container-tight py-6">
        <div class="text-center mb-4">
          <a href="."><img src="{{ asset('dist/img/logo.png') }}" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="." method="get" autocomplete="off" id="form-login">
        	{{ csrf_field() }}
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Login to your account</h2>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" placeholder="Enter username" name="username">
            </div>
            <div class="mb-2">
              <div class="input-group input-group-flat">
                <input type="password" class="form-control"  placeholder="Password"  autocomplete="off" name="password">
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                  </a>
                </span>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-check">
                <input type="checkbox" class="form-check-input"/>
                <span class="form-check-label">Remember me on this device</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('dist/libs/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Tabler Core -->
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>

    <script type="text/javascript">
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

		$('form#form-login').submit( function( e ) {
		  e.preventDefault();
		  var form_data = new FormData( this );

		  $.ajax({
		    type: 'post',
		    url: BASE_URL+'/api/login',
		    data: form_data,
		    cache: false,
		    contentType: false,
		    processData: false,
		    dataType: 'json',
		    beforeSend: function() {
		      
		    },
		    success: function(msg) {
		      if(msg.access_token){
		        setTimeout(function() {
		          swal({
		              title: "Sukses",
		              text: "Login Berhasil",
		              type:"success",
		              html: true
		          }, function() {
                $.ajax({
                  url: BASE_URL+'/api/me',
                  type: 'GET',
                  "headers": {
                    'Authorization': 'Bearer '+msg.access_token
                  },
                  dataType: 'JSON',
                  success: function(data, textStatus, jqXHR){
                    data.access_token = msg.access_token;
                    // data._token = '{{csrf_token()}}';
                    $.ajax({
                      type: 'post',
                      url: BASE_URL+'/login',
                      data: JSON.stringify(data),
                      "headers": {
                        'Content-Type': 'application/json'
                      },
                      cache: false,
                      contentType: false,
                      processData: false,
                      dataType: 'JSON',
                      success: function(msg) {
                        window.location.replace(BASE_URL+'/home');
                      }
                    })
                  },
                  error: function(jqXHR, textStatus, errorThrown){

                  },
                });
		          });
		        }, 500);
		      } else {
		        swal({
		          title: "Gagal",
		          text: msg.message,
		          showConfirmButton: true,
		          confirmButtonColor: '#0760ef',
		          type:"error",
		          html: true
		        });
		      }
		    },
        error: function (request, status, error) {
          swal({
            title: "Gagal",
            text: request.responseJSON.message,
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
          });
        }
		  })
		});
    </script>
  </body>
</html>