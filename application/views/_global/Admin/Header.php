<!-- Header -->
<div class="header d-print-none">
	<div class="header-container">
		<div class="header-left w-100">
			<div class="navigation-toggler">
				<a href="#" data-action="navigation-toggler">
					<i data-feather="menu"></i>
				</a>
			</div>

			<div class="header-logo">
				<a href="<?php echo base_url(); ?>Admin/Dashboard">
					<img class="logo mr-4 mt-4 logos" width="20%" src="<?php echo base_url(); ?>assets/images/logo-light.png" alt="logo" alt=" logo" style="margin-left: 3rem;" />
					<h4 class="mt-3 text-white ml-4">
						<!-- <?= $this->config->item('site_name') . " (" . $auth->panel . ")" ?> -->
					</h4>
				</a>
			</div>
		</div>

		<div class="header-body">
			<div class="header-body-right">
				<ul class="navbar-nav">

					<li class="nav-item dropdown d-none d-md-block">
						<a href="#" class="nav-link" title="Fullscreen" data-toggle="fullscreen">
							<i class="maximize" data-feather="maximize"></i>
							<i class="minimize" data-feather="minimize"></i>
						</a>
					</li>


					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" title="User menu" data-toggle="dropdown">
							<figure class="avatar avatar-sm">
								<img src="<?php echo base_url(); ?>assets/img/user.png" class="rounded-circle" alt="avatar" />
							</figure>
							<span class="ml-2 d-sm-inline d-none">
								<?= $this->session->userdata('name') ?>
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
							<div class="text-center py-4">
								<figure class="avatar avatar-lg mb-3 border-0">
									<img src="<?php echo base_url(); ?>assets/img/user.png" class="rounded-circle" alt="image" />
								</figure>
								<h5 class="text-center">
									<?= $this->session->userdata('role') ?>
								</h5>
								<div class="mb-2 small text-center text-muted">
									<?= $this->session->userdata('email') ?>
								</div>
							</div>
							<div class="list-group">
								<!-- <a href="<?= base_url('SuperAdmin/Main/profile/' . $this->session->userdata('userid')) ?>" class="list-group-item">View Profile</a> -->
								<a href="<?= site_url('auth/logout'); ?>" class="list-group-item text-danger">Sign
									Out!</a>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item header-toggler">
				<a href="#" class="nav-link">
					<i data-feather="arrow-down"></i>
				</a>
			</li>
		</ul>
	</div>
</div>
<!-- ./ Header -->