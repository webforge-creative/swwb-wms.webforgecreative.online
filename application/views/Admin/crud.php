<?php foreach ($crud->css_files as $file) : ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach ($crud->js_files as $file) : ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script>
	function fnExcelReport(test) {
		var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
		var textRange;
		var j = 0;
		tab = document.getElementById(test); // id of table

		for (j = 0; j < tab.rows.length; j++) {
			tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
			//tab_text=tab_text+"</tr>";
		}

		tab_text = tab_text + "</table>";
		tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
		tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
		tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");

		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
		{
			txtArea1.document.open("txt/html", "replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus();
			sa = txtArea1.document.execCommand("SaveAs", true, "Export Data.xls");
		} else //other browser not tested on IE 11
			sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

		return (sa);
	}
</script>
<script>
	$(document).ready(function() {
		// Check if DataTable is already initialized
		if (!$.fn.DataTable.isDataTable('#crudTable')) {
			// Initialize DataTable
			$('#crudTable').DataTable();
		}
	});
</script>
<style>
	#crudForm .pDiv {
		background-color: unset !important;
	}

	.form-input-box {
		float: left;
		width: 100%;
	}

	.flexigrid input[type="text"].form-control {
		width: 100% !important;
	}

	div.form-div input.datetime-input {
		width: 100% !important;
	}

	.flexigrid div.form-div input[type="text"]:focus,
	.flexigrid div.form-div textarea:focus {
		outline: 0;
		border-color: unset !important;
	}

	.flexigrid div.form-div textarea:focus,
	.flexigrid div.form-div input[type="text"]:focus,
	.flexigrid div.form-div select:focus {
		border: 1px solid #50535a !important;
	}

	.chosen-container-single .chosen-single-with-deselect span,
	.chosen-container-single .chosen-single span {
		color: black;
	}

	.dark .flexigrid tr td.sorted {
		background: #191919;
	}

	.dark .flexigrid div.pDiv {
		background: #202124;
		color: white;
		background: #2c2d32;
		border-color: #383a3f;
	}

	.dark .tDiv.p-2 {
		/* background: #202124; */
		color: white;
		background: #2c2d32;
		border-color: #383a3f;
	}

	.dark div#main-table-box {
		background: #202124;
		color: white;
		background: #2c2d32;
		border-color: #383a3f;
		background: #2c2d32;
		border-color: #383a3f;
	}

	.dark div#quickSearchBox {
		background: #202124;
		color: white;
		background: #2c2d32;
		border-color: #383a3f;
	}

	/* .dark .form-div input,
	.dark .form-div .form-control {
		background-color: white !important;
		color: black !important;
	} */

	div#ajax_list {
		/* border: 1px solid #ccc; */
		border-top: 0;
		border-bottom: 0;
		background: #2c2d32;
		border-color: #383a3f;
	}

	.dark .form-div {
		background: #2c2d32 !important;
		border-color: #383a3f !important;
	}

	.dark .form-field-box {
		color: white;
		background: #2c2d32 !important;
		border-color: #383a3f !important;
	}

	.dark .mDiv {
		color: white;
		background: #2c2d32 !important;
		border-color: #383a3f !important;
	}

	.dark .pDiv {
		color: white;
		background: #2c2d32 !important;
		border-color: #383a3f !important;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content">
	<div class="page-header d-md-flex justify-content-between">
		<div>
			<h3><?= $Heading; ?></h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="#"><?= $this->config->item('site_name') ?></a>
					</li>
					<li class="breadcrumb-item">
						<a href="#">Admin</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">
						<?= $subject; ?>
					</li>
				</ol>
			</nav>
		</div>
		<div class="mt-3 mt-md-0">
			<div class="crud-add-btn btn pr-2">

			</div>

			<?php if ($this->uri->segment(3) === 'payroll') : ?>
				<a href="<?= base_url('Admin/Finance/change_all_status_confirm');?>" class="btn btn-primary">
					<i class="fas fa-sync-alt"></i> &nbsp; Confirm All Transactions
			</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<?php if (isset($extra)) {
			echo $extra;
		} ?>
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div>
						<?php echo $crud->output; ?>
					</div>
				</div>
			</div>
			<!-- /.card -->
		</div>
	</div>
</div>
<!-- /.content-wrapper -->