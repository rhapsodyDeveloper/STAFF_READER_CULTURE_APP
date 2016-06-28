<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Rhapsody">
<?php $this->lang->load($this->config->item('admin_language'), $this->config->item('admin_language')); ?>
<title><?php echo $this->lang->line('page_title');?></title>
<!-- main css -->
<!-- The styles -->
<link id="bs-css" href="<?php echo admin_asset_url();?>css/bootstrap-journal.css" rel="stylesheet">
<style type="text/css">
  body {
	padding-bottom: 40px;
  }
  .sidebar-nav {
	padding: 9px 0;
  }
</style>
<link href="<?php echo admin_asset_url();?>css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo admin_asset_url();?>css/charisma-app.css" rel="stylesheet">
<link href="<?php echo admin_asset_url();?>css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
<link href='<?php echo admin_asset_url();?>css/fullcalendar.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/fullcalendar.print.css' rel='stylesheet' media='print'>
<link href='<?php echo admin_asset_url();?>css/chosen.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/uniform.default.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/colorbox.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/jquery.cleditor.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/jquery.noty.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/noty_theme_default.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/elfinder.min.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/elfinder.theme.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/jquery.iphone.toggle.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/opa-icons.css' rel='stylesheet'>
<link href='<?php echo admin_asset_url();?>css/uploadify.css' rel='stylesheet'>

<!-- jQuery -->
<script src="<?php echo admin_asset_url();?>js/jquery-1.7.2.min.js"></script>
<!-- data table plugin -->
<script src='<?php echo admin_asset_url();?>js/jquery.dataTables.min.js'></script>

<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- The fav icon -->
<link rel="shortcut icon" href="<?php echo admin_asset_url();?>img/favicon.png">

<script src="<?php echo admin_asset_url();?>js/jquery-ui-1.10.3.custom.min.js"></script>
<!-- transition / effect library -->
<script src="<?php echo admin_asset_url();?>js/bootstrap.min.js"></script>
<!-- library for cookie management -->
<script src="<?php echo admin_asset_url();?>js/jquery.cookie.js"></script>
<!-- calander plugin -->
<script src='<?php echo admin_asset_url();?>js/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='<?php echo admin_asset_url();?>js/jquery.dataTables.min.js'></script>

<!-- chart libraries start -->
<script src="<?php echo admin_asset_url();?>js/excanvas.js"></script>
<script src="<?php echo admin_asset_url();?>js/jquery.flot.min.js"></script>
<script src="<?php echo admin_asset_url();?>js/jquery.flot.pie.min.js"></script>
<script src="<?php echo admin_asset_url();?>js/jquery.flot.stack.js"></script>
<script src="<?php echo admin_asset_url();?>js/jquery.flot.resize.min.js"></script>
<!-- chart libraries end -->

<!-- select or dropdown enhancer -->
<script src="<?php echo admin_asset_url();?>js/jquery.chosen.min.js"></script>
<!-- checkbox, radio, and file input styler -->
<script src="<?php echo admin_asset_url();?>js/jquery.uniform.min.js"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo admin_asset_url();?>js/jquery.colorbox.min.js"></script>
<!-- rich text editor library -->
<script src="<?php echo admin_asset_url();?>js/jquery.cleditor.min.js"></script>
<!-- notification plugin -->
<script src="<?php echo admin_asset_url();?>js/jquery.noty.js"></script>
<!-- file manager library -->
<script src="<?php echo admin_asset_url();?>js/jquery.elfinder.min.js"></script>
<!-- star rating plugin -->
<script src="<?php echo admin_asset_url();?>js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo admin_asset_url();?>js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="<?php echo admin_asset_url();?>js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="<?php echo admin_asset_url();?>js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo admin_asset_url();?>js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="<?php echo admin_asset_url();?>js/charisma.js"></script>

</head>
<body>
<noscript>
<style>
#login-wrapper { display:none; }
#nojs { background:#FC3; border-bottom: 1px solid #F90; padding:15px; font-size:14px; font-weight:bold; text-align:center; }
</style>
<div id="nojs"><?php echo $this->lang->line('js_disabled');?></div>
</noscript>

<!-- topbar starts -->
<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a href="<?php echo base_url(); ?>admin/"><img src="<?php echo base_url('assets/admin/img/rhapsody-logo.png'); ?>" height="60px" width="129px" /></a>
			
			<!-- user dropdown starts -->
			<div class="btn-group pull-right" >
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-user"></i>
					<span class="hidden-phone">
						<?php echo $this->lang->line('welcome')?>, 
						<?php if ($this->session->userdata('name') == ""):?>
							<?php echo $this->session->userdata('username')?>
						<?php else: ?>
							<?php echo $this->session->userdata('name')?>
						<?php endif; ?>
					</span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo base_url(); ?>users/profile"><?php echo $this->lang->line('edit_profile')?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo base_url(); ?>admin/logout/"><?php echo $this->lang->line('logout');?></a></li>
				</ul>
			</div>
			<!-- user dropdown ends -->
			
		</div>
	</div>
</div>
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Main</li>
						<?php $list_of_modules = $this->config->item('modules_menu_icon');						
						if(isset($list_of_modules)){
							foreach ($list_of_modules as $key => $value) { 								
								$icon = $list_of_modules[$key];
								$icon = ($icon)?$icon:'icon-home';
								?>
								<li class=" <?php echo ($module_name == $key)?'active':'';?> noclass"><a class="ajax-link" href="<?php echo base_url().$key;?>" ><i class="<?php echo $icon;?>"></i><span class="hidden-tablet"> <?php echo $this->lang->line($key.'_menu');?></span></a></li>
							<?php }
						}?>
						
					</ul>
					<div class="hide">
						<label id="for-is-ajax" class="hidden-tablet" for="is-ajax"><input id="is-ajax" type="checkbox" checked> Ajax on menu</label>
					</div>
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->						
			<?php echo $content?>
			<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		<hr>

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>
		
	</div><!--/.fluid-container-->

<script type="text/javascript">    
$(function () {
$('#myTab a').tab();
});

$(document).ready(function(){
   $( ".datepicker" ).datepicker({ 
   	dateFormat: "<?php echo DATE_FORMAT_DATEPICKER;?>",
   	changeMonth: true,
  	changeYear: true });
});
</script>
</body>
</html>


