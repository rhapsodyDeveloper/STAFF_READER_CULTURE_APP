<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Quiz question main listing view file for admin
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
			<h2><i class="icon-question-sign"></i> <?php echo $this->lang->line('quiz_question')." ($bookName)"; ?></h2>
			<div class="box-icon">
				<a href="<?php echo base_url(); ?>quizquestion/create/<?php echo $quizId;?>" class="btn btn-primary"><i class="icon-plus icon-white"></i></a>
			</div>
		</div>
	<div id="container">				
		<div id="dynamic">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
				<th width="0%">id</th>
				<th  width="80%"><?php echo $this->lang->line('quiz_question');?></th>								
				<th width="20%">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2" class="dataTables_empty">Loading data from server</td>
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
		"sAjaxSource": "<?php echo base_url('quizquestion/get/'.$quizId);?>",
		"sPaginationType": "full_numbers",
		"aoColumns": [
            {"bVisible":false},            
            null,
            { "mDataProp": function( data, type, val ) {
            	return '<a class="btn btn-mini btn-info" href="<?php echo base_url(); ?>quizquestion/edit/<?php echo $quizId;?>/'+data[0]+'"><i title="Edit" data-rel="tooltip" class="icon-edit icon-white"></i></a> <a class="btn btn-mini btn-danger" href="<?php echo base_url(); ?>quizquestion/delete/<?php echo $quizId;?>/'+data[0]+'"><i title="Delete" data-rel="tooltip" class="icon-trash icon-white"></i></a>';
            },"bSortable":false,"sClass": "pagination-centered" }
        ]
	} );
	
	$(".btn-danger").live('click',function(){
		if(confirm("Are you sure you want to delete this data?"))
		location.href = "<?php echo base_url(); ?>quizquestion/delete/"+this.id;
		else
		return false;
	});	
});
</script>