<!doctype html>
<html lang="en">
<head>
    @include("layouts.partials.head")    
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    @include("layouts.partials.navbar")
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include("layouts.partials.sidebar")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @include("layouts.partials.breadcrumb")
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @yield("content")
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        @include("layouts.partials.footer")
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- jQuery -->
@include("layouts.partials.foot")
</body>
</html>