<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title><?= $auth->panel ?> Signup - <?= $this->config->item('site_name') ?></title>

    <!-- Icons font CSS-->
    <link href="<?php echo base_url() ?>assets/signupform/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url() ?>assets/signupform/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url() ?>assets/signupform/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url() ?>assets/signupform/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo base_url() ?>assets/signupform/css/main.css" rel="stylesheet" media="all">
</head>

<body>


    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">

        <div class="wrapper wrapper--w680">
            <?php if ($this->session->flashdata('age_error')) { ?>
                <div class="alertage">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    Your Age is under 18
                </div>
            <?php } ?>
            <br>
            <div class="card card-4">
                <div class="card-body">
                    <h1 style="text-align: center;margin-bottom:15px;">Register to be <?= $auth->panel ?> VERIFIED !</h1>
                    <hr>
                    <br>
                    <br>
                    <h2 style="text-align:left;margin-bottom:25px;">Basic Information</h2>

                    <form action="<?php echo base_url($auth->panel . '/Auth/signup/'); ?>" method="post" enctype="multipart/form-data">

                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">first name</label>
                                    <input class="input--style-4" type="text" name="firstname" value="<?php echo set_value('firstname'); ?>" required>
                                    <small><?php echo form_error('firstname'); ?></small>
                                </div>
                            </div>
                            <div class="col-1 sp">
                                <div class="input-group">
                                    <label class="label">middle name</label>
                                    <input class="input--style-4" type="text" name="middlename" value="<?php echo set_value('middlename'); ?>" required>
                                    <small><?php echo form_error('middlename'); ?></small>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">last name</label>
                                    <input class="input--style-4" type="text" name="lastname" value="<?php echo set_value('lastname'); ?>" required>
                                    <small><?php echo form_error('lastname'); ?></small>
                                </div>
                            </div>

                        </div>
                        <div class="row row-space">

                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">username</label>
                                    <input class="input--style-4" type="text" name="username" value="<?php echo set_value('username') ?>" required>
                                    <small><?php echo form_error('username'); ?></small>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Date Of Birth: <small>(age must be over 18)</small></label>
                                    <div class="input-group-icon">
                                        <abbr title="Age must be over 18">
                                            <input class="input--style-4 js-datepicker" type="text" name="date_of_birth" value="<?php echo set_value('date_of_birth') ?>" required>
                                            <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                            <small><?php echo form_error('date_of_birth'); ?></small>
                                        </abbr>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Gender</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="gender" value="<?php echo set_value('gender') ?>" required>
                                            <option disabled="disabled" selected="selected">Choose gender</option>
                                            <option>Male</option>
                                            <option>Female</option>

                                        </select>
                                        <div class="select-dropdown"></div>
                                        <small><?php echo form_error('gender'); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Phone Number</label>
                                    <input class="input--style-4" type="number" name="contact_number" value="<?php echo set_value('contact_number') ?>">
                                    <small><?php echo form_error('contact_number'); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" name="email" value="<?php echo set_value('email') ?>">
                                    <small><?php echo form_error('email'); ?></small>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Country</label>
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="country" value="<?php echo set_value('country') ?>" required>
                                            <option disabled="disabled" selected="selected">Choose country</option>
                                            <option>Pakistan</option>

                                        </select>
                                        <div class="select-dropdown"></div>
                                        <small><?php echo form_error('country'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">

                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Address</label>
                                    <input class="input--style-4" type="text" name="address" value="<?php echo set_value('address') ?>" required>
                                    <small><?php echo form_error('address'); ?></small>
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="label">Customer Image</label>
                                <input class="input--style-4" name="userimage" type="file" value="<?php echo set_value('userimage') ?>">
                                <small style="color:red;"><?php echo form_error('userimage'); ?></small>
                            </div>
                        </div>

                        <div class="p-t-15">
                            <button type="submit" name="signup" class="btn btn--radius-2 btn--blue">Submit</button>
                        </div>
                        <!-- <br>
                        <br>
                        <p class="text-muted">Already have an account?</p>
                        <a href="<?= base_url($auth->panel . '/Auth/signin/'); ?>" class="btn btn--radius-2 btn--blue">Sign
                            in!</a> -->

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="<?php echo base_url() ?>assets/signupform/vendor/jquery/jquery.min.js"></script>
    <!-- Vendor J<?php echo base_url() ?>assets/signupform/S-->
    <script src="<?php echo base_url() ?>assets/signupform/vendor/select2/select2.min.js"></script>
    <script src="<?php echo base_url() ?>assets/signupform/vendor/datepicker/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/signupform/vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="<?php echo base_url() ?>assets/signupform/js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->