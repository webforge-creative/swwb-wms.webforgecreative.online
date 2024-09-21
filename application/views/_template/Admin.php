<!DOCTYPE html>
<html lang="en">
<?php $this->view('_global/Admin/Head'); ?>

<body style="background-color: #01411c;">
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-icon"></div>
		<span>Loading...</span>
	</div>
	<!-- ./ Preloader -->


	<!-- Layout wrapper -->
	<div class="layout-wrapper">

		<?php $this->view('_global/Admin/Header'); ?>
		<!-- Content wrapper -->
		<div class="content-wrapper">
			<?php $this->view('_global/Admin/Navigation'); ?>
			<!-- Content body -->
			<div class="content-body">
				<?php echo $index ?>

				<?php $this->view('_global/Admin/Footer'); ?>

			</div>
			<!-- ./ Content body -->
		</div>
		<!-- ./ Content wrapper -->
	</div>
	<!-- ./ Layout wrapper -->

	<!-- Datatable JS -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/dataTable/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/examples/datatable.js"></script>

	<!-- Sweet Alert JS -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/examples/sweet-alert.js"></script>

	<!-- Input Mask -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/input-mask/jquery.mask.js"></script>

	<!-- Select JS -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/select2/js/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/examples/select2.js"></script>

	<!-- Javascript -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/datepicker/daterangepicker.js"></script>

	<!--Clockpicker JS-->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/clockpicker/bootstrap-clockpicker.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/examples/clockpicker.js"></script>

	<!-- Magnific popup -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/lightbox/jquery.magnific-popup.min.js"></script>

	<!-- Isotope -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/jquery.isotope.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/examples/pages/gallery.js"></script>

	<!-- Input Mask -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/vendors/input-mask/jquery.mask.js"></script>

	<!-- App scripts -->
	<script src="<?php echo base_url(); ?>assets/gogitemplate/assets/js/app.min.js"></script>
</body>

</html>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		// CNIC formatting and length validation
		document.querySelectorAll('input[name="worker_cnic"]').forEach(function(input) {
			input.addEventListener('input', function() {
				let value = this.value.replace(/[^0-9]/g, '');
				if (value.length > 13) {
					value = value.slice(0, 13); // Limit to 13 digits
				}
				if (value.length > 5) {
					value = value.slice(0, 5) + '-' + value.slice(5);
				}
				if (value.length > 13) {
					value = value.slice(0, 13) + '-' + value.slice(13);
				}
				this.value = value;
			});
		});

		document.querySelectorAll('input[name="daughter_cnic"]').forEach(function(input) {
			input.addEventListener('input', function() {
				let value = this.value.replace(/[^0-9]/g, '');
				if (value.length > 13) {
					value = value.slice(0, 13); // Limit to 13 digits
				}
				if (value.length > 5) {
					value = value.slice(0, 5) + '-' + value.slice(5);
				}
				if (value.length > 13) {
					value = value.slice(0, 13) + '-' + value.slice(13);
				}
				this.value = value;
			});
		});
	});
</script>

<script>
	$(document).ready(function() {

		$('#field-daughter_cnic').on('blur', function() {
			var daughter_cnic = $(this).val();

			$.ajax({
				url: '<?php echo site_url('Admin/Marriage_Grant/check_daughter_cnic_unique'); ?>',
				method: 'POST',
				data: {
					daughter_cnic: daughter_cnic
				},
				success: function(response) {
					var data = JSON.parse(response);
					if (!data.is_unique) {
						// Add error class and display the error message
						$('#field-daughter_cnic').addClass('is-invalid');
						if ($('#cnic-error').length == 0) {
							$('#field-daughter_cnic').after('<div id="cnic-error" class="invalid-feedback">This Daughter\'s CNIC is already in use.</div>');
						}
					} else {
						// Remove error class and hide the error message if CNIC is unique
						$('#field-daughter_cnic').removeClass('is-invalid');
						$('#cnic-error').remove();
					}
				}
			});
		});

		$('[data-input-mask="cnic"]').mask('0000000000000');
		$('[data-input-mask="phone"]').mask('000000000000')

		$('.select2').select2({
			placeholder: 'Select'
		});

		if ($('.image-popup').length) {
			$('.image-popup').magnificPopup({
				type: 'image',
				zoom: {
					enabled: true,
					duration: 300,
					easing: 'ease-in-out',
					opener: function(openerElement) {
						return openerElement.is('img') ? openerElement : openerElement.find('img');
					}
				}
			});
		}

		toastr.options = {
			timeOut: 3000,
			progressBar: true,
			showMethod: "slideDown",
			hideMethod: "slideUp",
			showDuration: 200,
			hideDuration: 200
		};
	});
</script>