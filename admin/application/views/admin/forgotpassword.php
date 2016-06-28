<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Pathik Gandhi">
        <?php $this->lang->load($this->config->item('admin_language'), $this->config->item('admin_language')); ?>
        <title><?php echo $this->lang->line('page_title'); ?></title>
        <!-- main css -->
        <!-- The styles -->
        <link id="bs-css" href="<?php echo admin_asset_url(); ?>css/bootstrap-journal.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>
        <link href="<?php echo admin_asset_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?php echo admin_asset_url(); ?>css/charisma-app.css" rel="stylesheet">

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The fav icon -->
        <link rel="shortcut icon" href="<?php echo admin_asset_url(); ?>img/favicon.ico">
    </head>
    <body>
        <noscript>
        <style>
            #login-wrapper { display:none; }
            #nojs { background:#FC3; border-bottom: 1px solid #F90; padding:15px; font-size:14px; font-weight:bold; text-align:center; }
        </style>
        <div id="nojs"><?php echo $this->lang->line('js_disabled'); ?></div>
        </noscript>
        <div class="container-fluid" id="login-wrapper">
            <div class="row-fluid">

                <div class="row-fluid">
                    <div class="span12 center login-header">
                        <h2><?php echo $this->lang->line('forgot_password_welcome_text'); ?></h2>
                    </div><!--/span-->
                </div><!--/row-->
                <div class="row-fluid">
                    <div class="well span5 center login-box">
                        <!-- messages section (error, warning, success) -->
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-error">
                                <ul>
                                    <?php echo validation_errors(); ?>
                                </ul>
                            </div>
                        <?php elseif ($this->session->flashdata('success')) : ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('success') ?>
                            </div>
                        <?php elseif ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-error">
                                <?php echo $this->session->flashdata('error') ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <?php echo $this->lang->line('forgot_password_text'); ?>
                            </div>
                        <?php endif; ?>
                        <!-- /messages section -->	

                        <!-- form elements section -->
                        <?php echo form_open('admin/forgotpassword', array('name' => "forgotpassword", 'class' => "form-horizontal")); ?>
                        <fieldset>
                            <div class="input-prepend" title="<?php echo $this->lang->line('email'); ?>" data-rel="tooltip">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input autofocus class="input-large span10" name="email" id="username" type="text"  value="<?php echo set_value('email'); ?>" />
                            </div>
                            <div class="clearfix"></div>
                            <p class="center span5">
                                <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('finish'); ?></button>
                                <a  href="<?php echo base_url(); ?>admin/login">Sign In</a>
                            </p>
                        </fieldset>
                        <?php echo form_close(); ?>		
                        <!-- /form elements section -->	
                    </div><!--/span-->
                </div><!--/row-->
            </div><!--/fluid-row-->

        </div><!--/.fluid-container-->

        <!-- external javascript
                ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->

        <!-- jQuery -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery-1.7.2.min.js"></script>
        <!-- jQuery UI -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery-ui-1.8.21.custom.min.js"></script>
        <!-- transition / effect library -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap.min.js"></script>
        <!-- library for advanced tooltip -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-tooltip.js"></script>
        <!-- popover effect library -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-popover.js"></script>
        <!-- button enhancer library -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-button.js"></script>
        <!-- accordion library (optional, not used in demo) -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-collapse.js"></script>
        <!-- carousel slideshow library (optional, not used in demo) -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-carousel.js"></script>
        <!-- autocomplete library -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-typeahead.js"></script>
        <!-- tour library -->
        <script src="<?php echo admin_asset_url(); ?>js/bootstrap-tour.js"></script>
        <!-- library for cookie management -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.cookie.js"></script>
        <!-- calander plugin -->
        <script src='<?php echo admin_asset_url(); ?>js/fullcalendar.min.js'></script>
        <!-- data table plugin -->
        <script src='<?php echo admin_asset_url(); ?>js/jquery.dataTables.min.js'></script>

        <!-- chart libraries start -->
        <script src="<?php echo admin_asset_url(); ?>js/excanvas.js"></script>
        <script src="<?php echo admin_asset_url(); ?>js/jquery.flot.min.js"></script>
        <script src="<?php echo admin_asset_url(); ?>js/jquery.flot.pie.min.js"></script>
        <script src="<?php echo admin_asset_url(); ?>js/jquery.flot.stack.js"></script>
        <script src="<?php echo admin_asset_url(); ?>js/jquery.flot.resize.min.js"></script>
        <!-- chart libraries end -->

        <!-- select or dropdown enhancer -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.chosen.min.js"></script>
        <!-- checkbox, radio, and file input styler -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.uniform.min.js"></script>
        <!-- plugin for gallery image view -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.colorbox.min.js"></script>
        <!-- rich text editor library -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.cleditor.min.js"></script>
        <!-- notification plugin -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.noty.js"></script>
        <!-- file manager library -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.elfinder.min.js"></script>
        <!-- star rating plugin -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.raty.min.js"></script>
        <!-- for iOS style toggle switch -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.iphone.toggle.js"></script>
        <!-- autogrowing textarea plugin -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.autogrow-textarea.js"></script>
        <!-- multiple file upload plugin -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.uploadify-3.1.min.js"></script>
        <!-- history.js for cross-browser state change on ajax -->
        <script src="<?php echo admin_asset_url(); ?>js/jquery.history.js"></script>
        <!-- application script for Charisma demo -->
        <script src="<?php echo admin_asset_url(); ?>js/charisma.js"></script>
    </body>
</html>