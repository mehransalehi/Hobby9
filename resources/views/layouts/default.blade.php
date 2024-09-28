<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    @include('includes.head')
</head>
<body>
<div class="header-margin"></div>
<div class="container-fluid">

    <header>
        @include('includes.header')
    </header>

    <div id="main">
            @yield('content')
    </div>

    <footer class="container-fluid" id="footerWrapper">
        @include('includes.footer')
    </footer>

</div>
</body>
</html>
