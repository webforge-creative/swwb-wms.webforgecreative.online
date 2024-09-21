<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $page_title; ?></title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?= base_url("assets/images/favicon.png"); ?>" />

	<!-- Main css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/bundle.css" type="text/css">

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

	<!-- Fontawesome Icon -->
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	
	<!-- Time Css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/clockpicker/bootstrap-clockpicker.min.css" type="text/css">

	<!-- Date CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/gogitemplate/vendors/datepicker/daterangepicker.css" type="text/css">

	<!-- DataTable -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/dataTable/datatables.min.css" type="text/css">

	<!--Select Dropdonw-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/select2/css/select2.min.css" type="text/css">

	<!-- Magnific popup -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/vendors/lightbox/magnific-popup.css" type="text/css">

	<!-- App css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/gogitemplate/assets/css/app.min.css" type="text/css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->

	<!-- Main scripts (JQUERY Included (3.4.1)) -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/bundle.js"></script>

	<!-- ckeditor5 JS -->
	<!-- <script src="<?php echo base_url('assets/gogitemplate/vendors/ckeditor5/ckeditor.js'); ?>"></script> -->

	<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<style>

	@media (max-width: 1200px){
		.logo {
			height: 90% !important;
    		margin-top: 1px !important;
		}

		.header-logo > a > h1 {
			margin-top: 10px !important;
		}
	}

	.select2-container .select2-selection--single {
		height: 36px !important;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 36px !important;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 34px !important; 
	}


</style>