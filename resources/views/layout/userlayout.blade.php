<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login -Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header class="sticky-top">
        <nav class="d-flex p-3 bg-color align-items-center">
            <div class="brand">
                <h5 class="text-white font-weight-bold">EXPENSE MANAGER</h5>
            </div>
            <div class="tab ml-auto">
                <a href="/" class="btn bg-outline text-white">
                    <i class="fa fa-power-off"></i>
                    Logout
                </a>
            </div>
        </nav>
    </header>
    <div class="container-fluid">
        <main class="main">

            @yield('content')

        </main>
    </div>
    <script type="text/javascript">
        const FILTER = document.querySelector("#filter");
        const BOARD = document.querySelector("#board");

        function closeFilter() {
            FILTER.style.display = "none";
            BOARD.style.marginLeft = "0em";
            BOARD.style.width = "75%";
        }

        function openFilter() {
            FILTER.style.display = "block";
            BOARD.style.marginLeft = "18em";
            BOARD.style.width = "54%";
        }
    </script>
</body>

</html>
