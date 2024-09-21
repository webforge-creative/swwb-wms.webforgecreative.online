<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Workers Welfare Board</title>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url("assets/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/css/all.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/css/style-login.css") ?>">
    <style>
        .loginform {
            background-color: #01411c !important;
        }

        .detail-box {
            background-color: #fff !important;
        }

        .form-control {
            background-color: #e8f0fe !important;
        }

        .input-group-text {
            background-color: #231f20 !important;
        }

        .btn-custom {
            background-color: #231f20 !important;
            color: #fff !important;
        }

        ::placeholder {
            color: #002c82 !important;
            opacity: 1;
            /* Firefox */
        }

        ::-ms-input-placeholder {
            /* Edge 12-18 */
            color: #002c82 !important;
        }
    </style>
</head>

<body class="form-login-body">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto login-desk">
                <div class="row">
                    <div class="col-md-7 detail-box">
                        <!-- <img class="logo" src="<?= base_url() ?>assets/images/logo.png" alt="Hospital Logo"> -->
                        <div class="detailsh">
                            <img class="help" src="<?= base_url() ?>assets/images/logo.png" alt="Support Image" style="width: 360px;">
                            <h3>Workers Welfare Board Sindh CMS</h3>
                            <p>Our support team is always available to help you.</p>
                        </div>
                    </div>
                    <div class="col-md-5 loginform">
                        <h4>Welcome Back</h4>
                        <p>Please sign in to access your account.</p>
                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="login-det">
                            <?php echo form_open(base_url('auth/authenticate')); ?>
                            <div class="form-group">
                                <label for="username">Username or Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="identity" name="identity" placeholder="Enter Username or Email" style="color: black !important;" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" style="color: black !important;" required>
                                </div>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <button type="submit" class="col-md-12 btn btn-custom">Login</button>
                            <?php echo form_close(); ?>
                            <br>
                            <p class="forget"><a href="#">Forgot Password?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="<?= base_url() ?>assets/js/jquery-3.5.1.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/script.js"></script>

</html>