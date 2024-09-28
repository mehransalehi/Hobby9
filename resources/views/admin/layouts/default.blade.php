<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    @include('admin.includes.head')
</head>
<body>
    <header>
        @include('admin.includes.header')
    </header>

    <div style="display:flex">
      <div class="main-content">
        @yield('content')
      </div>

      @include('admin.includes.sidebar')
    </div>
    <footer id="footer">
        @include('admin.includes.footer')
    </footer>
</body>
</html>
