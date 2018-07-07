<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
  <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet" />
  <!-- Styles -->
  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="app">

    @include('admin.partials.spinner')

    <div>
      <!-- #Left Sidebar ==================== -->
      @include('admin.partials.sidebar')

      <!-- #Main ============================ -->
      <div class="page-container">
        <!-- ### $Topbar ### -->
        @include('admin.partials.topbar')

        <!-- ### $App Screen Content ### -->
        <main class='main-content bgc-grey-100'>
          <div id='mainContent'>
            <div class="container-fluid">

              <h4 class="c-grey-900 mT-10 mB-30">@yield('page-header')</h4>
        
              @include('admin.partials.messages')
              @yield('content')

            </div>
          </div>
        </main>

        <!-- ### $App Screen Footer ### -->
				<footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
					<span>Copyright © 2018 Designed by <a href="https://colorlib.com" target='_blank' title="Colorlib">Colorlib</a>. All rights reserved.</span>
				</footer>
      </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
               integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
               crossorigin="anonymous">
      </script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
    (function(){
        new ClipboardJS('.clipboardjscopy');
        })();
    </script>
    @yield('footer_scripts')

</body>
</html>
