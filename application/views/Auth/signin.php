<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- <?= $auth->panel ?> -->
	<title>
		<?= $this->config->item('site_name') ?>
	</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png" />

	<!-- Plugin styles -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/bundle.css" type="text/css">

	<!-- App styles -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/assets/css/app.min.css" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
		integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/fontawesome.min.css"
		integrity="sha512-SgaqKKxJDQ/tAUAAXzvxZz33rmn7leYDYfBP+YoMRSENhf3zJyx3SBASt/OfeQwBHA1nxMis7mM3EV/oYT6Fdw=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/brands.min.css"
		integrity="sha512-9YHSK59/rjvhtDcY/b+4rdnl0V4LPDWdkKceBl8ZLF5TB6745ml1AfluEU6dFWqwDw9lPvnauxFgpKvJqp7jiQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/regular.min.css"
		integrity="sha512-WidMaWaNmZqjk3gDE6KBFCoDpBz9stTsTZZTeocfq/eDNkLfpakEd7qR0bPejvy/x0iT0dvzIq4IirnBtVer5A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/solid.min.css"
		integrity="sha512-yDUXOUWwbHH4ggxueDnC5vJv4tmfySpVdIcN1LksGZi8W8EVZv4uKGrQc0pVf66zS7LDhFJM7Zdeow1sw1/8Jw=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/svg-with-js.min.css"
		integrity="sha512-FTnGkh+EGoZdexd/sIZYeqkXFlcV3VSscCTBwzwXv1IEN5W7/zRLf6aUBVf2Ahdgx3h/h22HNzaoeBnYT6vDlA=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />



	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/v4-font-face.min.css"
		integrity="sha512-Q9fw7EiujSMJQlnpqJx4ZgUhl2G01A3w4Mobk7icTgea7JYU219Z01Qvfp0QpkA9qLb4MUX+g77X/lvoBPHzoQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/v4-shims.min.css"
		integrity="sha512-4yDn1AmIfvyydlRqsIga3JribpHu5HdkIFTBZjJPcz01tcsd8B9UwObwZCGez1ZOyUNnxjNQNcZEElhkguF76Q=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/v5-font-face.min.css"
		integrity="sha512-W+lqPhhunEwz9brBt8usGl0j+mtLGggjFC/9RHzyFyDKe/SSkCgQ08Y9ZZSNYez5biH7IhJU66+iUiQagtSm8w=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />


	<!-- <style>
		.form-membership {
			background-image: url('<?php echo base_url(); ?>assets/img/learning.jpg');
			background-size: cover;
		}

		body.dark.form-membership .form-wrapper {
			background-color: #14476B;
			opacity: 0.75;
		}

		body.dark.form-membership .form-wrapper #logo img {
			display: block;
		}

		body.dark.form-membership .form-wrapper #logo img:not(.logo-light) {
			display: none;
		}

		body.dark .form-control {
			border-color: #5c5f68;
			background-color: #14476B;
			color: white;
		}

		body.dark .form-control:focus {
			border-color: #5c5f68;
			background-color: #14476B;
		}
	</style> -->

	<style>
		/* img {
			margin-bottom: 50px !important;
		} */

		.membership {
			margin-top: 1.5em;
		}

		body {
			background-color: #002c82;
		}

		.btn-group-justified {
			display: table;
			width: 100%;
			table-layout: fixed;
			border-collapse: separate;
		}

		.btn_blue {
			background: #002C82 !important;
		}

		.login {
			max-height: 100%;
			border-radius: 4px;
		}

		.formtop {
			background: transparent;
			border-bottom: 1px solid rgba(255, 255, 255, 0.19);
		}

		.topleft {
			float: left;
			width: 75%;
			/* padding-top: 33px; */
		}

		.topright {
			float: left;
			width: 25%;
			padding-top: 15px;
			font-size: 50px;
			text-align: right;
			color: #002c82;
		}

		.fonts {
			margin-top: 0;
			font-size: 18px;
			font-weight: 400;
		}

		.inputs {
			height: 40px;
			border: 1px solid #999;
		}

		.separatline {
			background: rgba(221, 221, 221, 0.33);
			margin-left: 30px;
			width: 1px;
			height: 500px;
		}

		.loginright {
			text-align: left;
			color: #bdbcbc;
			max-height: 400px;
			overflow: auto;
			position: relative;
			width: 100%;
			max-width: 100%;
			height: 400px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}

		.inner {
			padding: 70px 0 100px 0;
			margin-top: 2.5em;
		}

		.loginright h3 {
			font-size: 20px;
			color: #fff;
			margin-top: 10px;
			line-height: normal;
			font-weight: 500;
			padding-bottom: 10px;
		}

		.loginright h4 {
			font-weight: normal;
			line-height: normal;
			font-size: 16px;
		}

		.loginright p {
			line-height: 18px;
			font-size: 12px;
		}

		.logdivider {
			background: rgba(255, 255, 255, 0.11);
			clear: both;
			width: 100%;
			height: 1px;
			margin: 15px 0 15px;
		}

		button.btn:hover {
			opacity: 100 !important;
			color: #fff;
			background: #066dd4;
		}

		button.btn {
			margin: 0;
			padding: 0 20px;
			vertical-align: middle;
			border: 0;
			font-size: 16px;
			font-weight: 400;
			color: #fff;
			-moz-border-radius: 4px;
			border-radius: 4px;
			text-shadow: none;
			transition: all .3s;
			height: 40px;
			width: 100%;
			display: block;
		}

		a.more {
			text-decoration: none;
			color: #fff;
			font-size: 12px;
		}

		.signin {
			margin-bottom: 1em;
		}

		.icon {
			margin-bottom: 2em;
		}

		.logins {
			margin-bottom: 1.2em;
		}

		.logwrap {
			margin-top: 8.5em;
		}
	</style>
</head>

<body class="form-membership membership">
	<!-- begin::preloader-->
	<div class="preloader">
		<div class="preloader-icon"></div>
	</div>
	<!-- end::preloader -->

	<div class="form-wrapper logwrap">
		<!-- logo -->
		<div class="logo icon">
			<img src="<?php echo base_url('assets\img\JDC_Foundation_Logo.png'); ?>" style="width:40%;" alt="image">
		</div>
		<h4 class="text-center logins">Login</h4>
		<!-- ./ logo -->
		<div class="container">
			<div class="superadminloginsection">
				<?php
				if ($this->session->flashdata('error_msg')) { ?>
					<div class="alert alert-danger alert-dismissible" role="alert">
						Invalid User Email Or Password
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<i class="ti-close"></i>
						</button>
					</div>
				<?php } ?>
				<form action="<?php echo base_url('Auth/login'); ?>" method="post">
					<div class="form-group">
						<!-- <input name="username" type="text" class="form-control username" placeholder="User Name"
							required autofocus> -->
						<input name="email" type="email" class="form-control username" placeholder="User Email" required
							autofocus>
					</div>
					<div class="form-group">
						<input name="password" type="password" class="form-control password" placeholder="Password"
							required>
					</div>
					<button type="submit" class="btn btn-primary btn-block btn_blue mb-3">Sign in</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Plugin scripts -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/bundle.js"></script>

	<!-- App scripts -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/app.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
		integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/brands.min.js"
		integrity="sha512-KYlRezs7yAa59UnX6zAvY7I96Te02kycQn02Sr6FU/fBpxcXAwumRe5DHVrqVnWTt9HY/PktrAPZzSe9UE1Yxg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/conflict-detection.min.js"
		integrity="sha512-Xmx4mYaVSbyVq8IV38jkKf2OZzXfoWTb1i8wYcAVSrNSIDBs7ZgGQzXiNv4Q6PZIO2maDXRjCTs5yaQpVMDs+A=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/fontawesome.min.js"
		integrity="sha512-c41hNYfKMuxafVVmh5X3N/8DiGFFAV/tU2oeNk+upk/dfDAdcbx5FrjFOkFhe4MOLaKlujjkyR4Yn7vImrXjzQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/regular.min.js"
		integrity="sha512-RNA9D6w1BGWFIVSGkGBsEEgVxoGNu7mtLZ+QQvwvoZHJHyoK19FF5R4xeM4AmdzreiwRzu8r0Xs89kFrOPh2zw=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/solid.min.js"
		integrity="sha512-apZ8JDL5kA1iqvafDdTymV4FWUlJd8022mh46oEMMd/LokNx9uVAzhHk5gRll+JBE6h0alB2Upd3m+ZDAofbaQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/v4-shims.min.js"
		integrity="sha512-jwXCc38I7s9ikGI6qbqIcktgJDVVppplsNQ5DgW7VbUZCVvdo31qQnpgiU7aDQRa3pETbn9LPnOo97r4Id5/cg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<!-- <script>
		$(document).ready(function () {

			$(".role").change(function () {
				var role = $(".role option:selected").val();
				$.ajax({
					url: "<?= base_url('SuperAdmin/Auth/userfetchdata') ?>",
					method: "POST",
					data: {
						role: role
					},
					async: true,
					dataType: "json",
					success: function (data) {
						$(".username").val(data.username);
					}
				});
			});

		});
	</script> -->
</body>

</html>