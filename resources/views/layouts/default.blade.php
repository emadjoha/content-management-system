

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{asset('../resources/sass/nav_bar.css')}}"  >

        @yield('head')
    </head>
    <body>
        <div id="spc_lang">
            <header >
                    @include('layouts.nav_bar')
                    @yield('header')
            </header>


            {{-- content page --}}


            <div class="container">
                <?php  $lang_id = session('lang');
                $dir = \App\Language::find($lang_id); ?>
                <script>
                    if( '<?= $dir->align ?>' == 1 ){ // arabic ...
                        document.getElementById('spc_lang').dir = 'rtl';
                        document.getElementById('spc_lang').style.textAlign = 'right';
                    }else{
                        document.getElementById('spc_lang').dir = 'ltr';
                        document.getElementById('spc_lang').style.textAlign = 'left';
                    }
                </script>
                @yield('content')
            </div>

            <footer>
                @yield('footer')
            </footer>
        </div>
    </body>
</html>