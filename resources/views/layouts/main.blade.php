<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('title')</title>
    <!-- CSS files -->
    <link href="{{ asset('dist/libs/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/libs/flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/libs/datatables/datatables.min.css') }}" rel="stylesheet"/>
    {{-- <link href="{{ asset('dist/libs/jqueryui/jquery-ui.min.css') }}" rel="stylesheet"/> --}}
    {{-- <link href="{{ asset('dist/libs/sweetalert/sweetalert.css') }}" rel="stylesheet"/> --}}
    <link href="{{ asset('dist/libs/sweetalert/sweetalert-5.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/vendor/select2/css/select2.min.css') }}" rel="stylesheet"/>

    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet"/>

    <style type="text/css">
      #overlay {
        position: absolute;
        display: flex;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        cursor: pointer;
      }

      .spinner {
        height: 60px;
        width: 60px;
        margin: auto;
        display: flex;
        position: absolute;
        -webkit-animation: rotation .6s infinite linear;
        -moz-animation: rotation .6s infinite linear;
        -o-animation: rotation .6s infinite linear;
        animation: rotation .6s infinite linear;
        border-left: 6px solid rgba(0, 174, 239, .15);
        border-right: 6px solid rgba(0, 174, 239, .15);
        border-bottom: 6px solid rgba(0, 174, 239, .15);
        border-top: 6px solid rgba(0, 174, 239, .8);
        border-radius: 100%;
      }

      #overlay-2 {
        position: absolute;
        display: flex;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        cursor: pointer;
      }

      .spinner-2 {
        height: 60px;
        width: 60px;
        margin: auto;
        display: flex;
        position: absolute;
        -webkit-animation: rotation .6s infinite linear;
        -moz-animation: rotation .6s infinite linear;
        -o-animation: rotation .6s infinite linear;
        animation: rotation .6s infinite linear;
        border-left: 6px solid rgba(0, 174, 239, .15);
        border-right: 6px solid rgba(0, 174, 239, .15);
        border-bottom: 6px solid rgba(0, 174, 239, .15);
        border-top: 6px solid rgba(0, 174, 239, .8);
        border-radius: 100%;
      }

      @-webkit-keyframes rotation {
        from {
          -webkit-transform: rotate(0deg);
        }
        to {
          -webkit-transform: rotate(359deg);
        }
      }

      @-moz-keyframes rotation {
        from {
          -moz-transform: rotate(0deg);
        }
        to {
          -moz-transform: rotate(359deg);
        }
      }

      @-o-keyframes rotation {
        from {
          -o-transform: rotate(0deg);
        }
        to {
          -o-transform: rotate(359deg);
        }
      }

      @keyframes rotation {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(359deg);
        }
      }

      .spanner{
        position:absolute;
        top: 40%;
        left: 0;
        background: #2a2a2a;
        width: 100%;
        display:block;
        text-align:center;
        height: 300px;
        color: #FFF;
        transform: translateY(-50%);
        z-index: 1000;
        visibility: hidden;
      }

/*          .overlay{
        position: fixed;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        visibility: hidden;
      }*/

      .overlay {
       width: 100%;
       height: 100%;
       left: 0px;
       background: rgba(0,0,0,0.5);
       position: absolute;
       opacity: 0;
       filter: alpha(opacity=0);
       z-index: 1;
       visibility: hidden;
      }

      .loader,
      .loader:before,
      .loader:after {
        border-radius: 50%;
        width: 2.5em;
        height: 2.5em;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation: load7 1.8s infinite ease-in-out;
        animation: load7 1.8s infinite ease-in-out;
      }
      .loader {
        color: #ffffff;
        font-size: 10px;
        margin: 80px auto;
        position: relative;
        text-indent: -9999em;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
      }
      .loader:before,
      .loader:after {
        content: '';
        position: absolute;
        top: 0;
      }
      .loader:before {
        left: -3.5em;
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
      }
      .loader:after {
        left: 3.5em;
      }
      @-webkit-keyframes load7 {
        0%,
        80%,
        100% {
          box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
          box-shadow: 0 2.5em 0 0;
        }
      }
      @keyframes load7 {
        0%,
        80%,
        100% {
          box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
          box-shadow: 0 2.5em 0 0;
        }
      }

      .show{
        visibility: visible;
      }

      .spanner, .overlay{
        opacity: 0;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
      }

      .spanner.show, .overlay.show {
        opacity: 1
      }

      #main-table_wrapper {
        padding-left: 20px; 
        padding-right: 20px; 
        padding-top: 20px; 
        padding-bottom: 20px;
      }

      table.dataTable.no-footer {
        border-color: #DCDCDC;
      }

      a.nostyle:link {
          text-decoration: inherit;
          color: inherit;
      }

      a.nostyle:visited {
          text-decoration: inherit;
          color: inherit;
      }

      a.nostyle:hover {
          text-decoration: inherit;
          color: inherit;
      }
    </style>
  </head>
  <script type="text/javascript">
    let BASE_URL = '{{ url('/') }}';
    let TOKEN = 'Bearer {{Session::get('_access_token')}}';
  </script>
  @php
      $user = App\Models\User::find(Session::get('_id'));
  @endphp
  {{-- {{dd(Session::get('_access_token'))}} --}}
  <body class="antialiased">
    <div class="overlay"></div>
    <div class="spanner">
      <div class="loader"></div>
      <p>Server Is Collecting Data For You, Please Wait.</p>
    </div>
    <div class="page">
      <header class="navbar navbar-expand-md navbar-dark navbar-overlap d-print-none">
        <div class="container-xl">
          @include('layouts.header')
          @include('layouts.navbar')
        </div>
      </header>
      <div class="content">
        <div class="container-xl">
          @yield('content')
        </div>
        @include('layouts.footer')
      </div>
    </div>
    @yield('modal')
    <!-- Libs JS -->
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('dist/libs/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/libs/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('dist/libs/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('dist/libs/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('dist/libs/flatpickr/dist/plugins/rangePlugin.js') }}"></script>
    <script src="{{ asset('dist/libs/datatables/datatables.min.js') }}"></script>
    {{-- <script src="{{ asset('dist/libs/sweetalert/sweetalert.min.js') }}"></script> --}}
    <script src="{{ asset('dist/libs/sweetalert/sweetalert-5.js') }}"></script>
    <script src="{{ asset('dist/vendor/select2/js/select2.min.js') }}"></script>
    
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>
    @yield('script')
    <script type="text/javascript">
      function addCommas(nStr)
      {
          nStr += '';
          x = nStr.split('.');
          x1 = x[0];
          x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
      }

      function generalInitials(str)
      {
        if(str) {
          var array = str.split(' ');
          switch ( array.length ) {
              case 1:
                return array[0].charAt(0).toUpperCase();
                break;
              default:
                return array[0].charAt(0).toUpperCase() + array[ array.length -1 ].charAt(0).toUpperCase();
          }
        }
        return false;
      }

      function stringToSlug (str) {
          str = str.replace(/^\s+|\s+$/g, ''); // trim
          str = str.toLowerCase();
        
          // remove accents, swap ñ for n, etc
          var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
          var to   = "aaaaeeeeiiiioooouuuunc------";
          for (var i=0, l=from.length ; i<l ; i++) {
              str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
          }

          str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
              .replace(/\s+/g, '-') // collapse whitespace and replace by -
              .replace(/-+/g, '-'); // collapse dashes

          return str;
      }

      function showAlertOnSubmit(params, modal, table, reload) {
        if(params.status == 'success'){
          setTimeout(function() {
            swal({
              title: "Sukses",
              text: params.message,
              type:"success"
            }).then(function() {
              if (modal) {
                $(modal).modal('hide');
              }
              if (table) {
                $(table).DataTable().ajax.reload( null, false );
              }
              if (reload) {
                window.location.replace(reload);
              }
            });
          }, 200);
        } else {
          swal({
            title: "Gagal",
            text: params.message,
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error"
          });
        }
      }

      function showDeletePopup(url, token, modal, table, reload) {
        swal({
          title: 'Apakah anda yakin?',
          text: "Apakah anda yakin menghapus data ini?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          // cancelButtonColor: '#d33',
          confirmButtonText: 'Ya!',
          cancelButtonText: 'Tidak',
        }).then((result) => {
          if (result) {
            $.ajax({
              url: url,
              "headers": {
                'Authorization': token
              },
              type: "DELETE"
            })
            .done( function( data ) {
              swal( "Dihapus!", "Data has succesfully deleted!", "success" );
              if (modal) {
                $(modal).modal('hide');
              }
              if (table) {
                $(table).DataTable().ajax.reload( null, false );
              }
              if (reload) {
                window.location.replace(reload);
              }
            } )
            .fail( function( data ) {
              swal( "Oops", "We couldn't connect to the server!", "error" );
            } );
          }
        })
      }
    </script>
  </body>
</html>