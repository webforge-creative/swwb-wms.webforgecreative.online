<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 Error</title>

    <!-- Plugin styles -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/gogitemplate/vendors/bundle.css" type="text/css">

    <!-- App styles -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/gogitemplate/assets/css/app.min.css" type="text/css">

    <style>
        .error-page {
            background-color: #191919;
        }
    </style>
</head>

<body class="error-page">

    <div class="text-light">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <img class="img-fluid" src="<?= base_url() ?>/assets/gogitemplate/assets/media/svg/404.svg" alt="...">
                <h3 class="text-light">Page not found</h3>
                <p class="text-muted">The page you want to go is not currently available</p>
                <a href="<?= base_url() . $this->session->userdata('role') ?>/Dashboard" class="btn btn-primary">Home</a>
                <button class="btn btn-light ml-2" onclick="goBack()">Back</button>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

    <!-- Plugin scripts -->
    <script src="<?= base_url() ?>/assets/gogitemplate/vendors/bundle.js"></script>

    <!-- App scripts -->
    <script src="<?= base_url() ?>/assets/gogitemplate/assets/js/app.min.js"></script>
</body>

</html>