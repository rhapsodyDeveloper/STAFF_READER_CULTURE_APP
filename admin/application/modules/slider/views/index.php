<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Slider  main listing view file for admin
	 * 
	 * @author: Pratyush Dimri
	 */
if($this->session->flashdata('info')){?>
<div class="alert alert-success">
<?php echo $this->session->flashdata('info')?>
</div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
<div class="alert alert-error">
<?php echo $this->session->flashdata('error')?>
</div>
<?php } ?>
<link href="<?php echo admin_asset_url();?>css/datatable.css" rel="stylesheet">
<div class="row-fluid">		
	<div class="box-header well" data-original-title>
			<h2><i class="icon-film"></i> <?php echo $this->lang->line('slider'); ?></h2>
			<div class="box-icon">
				<a href="<?php echo base_url(); ?>slider/create" class="btn btn-primary"><i class="icon-plus icon-white"></i></a>
			</div>
		</div>
	<div id="container">				
		<div id="dynamic">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
				<th width="0%">id</th>
				<th  width="70%"><?php echo $this->lang->line('slider_name');?></th>
				<th width="20%"><?php echo $this->lang->line('slider_status');?></th>
				<th width="10%">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="3" class="dataTables_empty">Loading data from server</td>
				</tr>
			</tbody>			
		</table>       
		</div>
		
	</div><!--/span-->

</div><!--/row-->
<script type="text/javascript">
	

$(document).ready(function () {
	
	$('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?php echo base_url('slider/get');?>",
		"sPaginationType": "full_numbers",
		"aoColumns": [
            {"bVisible":false},
            null,
            null,
            { "mDataProp": function( data, type, val ) {
            	return '<a class="btn btn-mini btn-info" href="<?php echo base_url(); ?>slider/edit/'+data[0]+'"><i title="Edit" data-rel="tooltip" class="icon-edit icon-white"></i></a> <a class="btn btn-mini btn-danger" href="<?php echo base_url(); ?>slider/delete/'+data[0]+'"><i title="Delete" data-rel="tooltip" class="icon-trash icon-white"></i></a>';
            },"bSortable":false }
        ]
	} );
	
	$(".btn-danger").live('click',function(){
		if(confirm("Are you sure you want to delete this data?"))
		location.href = "<?php echo base_url(); ?>slider/delete/"+this.id;
		else
		return false;
	});	
});
</script>