<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Feedback main listing view file for admin
	 * 
	 * @author: Pratyush Dimri
	 */
?>
<link href="<?php echo admin_asset_url();?>css/datatable.css" rel="stylesheet">
<script src='<?php echo admin_asset_url();?>js/reloadajax.js'></script>
<div class="row-fluid">		
	<div class="box-header well" data-original-title>
			<h2><i class="icon-plan"></i> <?php echo $this->lang->line('feedback'); ?></h2>			
		</div>
	<div id="container">				
		<div id="dynamic">
                    
                <!--filter div start-->
			<div class="filter-div form-horizontal">
			
				
				<div class="range">
					
					<div class="range-div-title">Range</div>
					<div class="range-div">					
						<select id="year" name="year" data-rel="chosen">							
						    <option value='2014'>2014</option>
							<option value='2013'>2013</option>
							<option value='2012'>2012</option>
							<option value='2011'>2011</option>
						</select>
					</div>				
					<div class="range-div">                	
						<input type="text" name="from" id="from" class="input-small filter-input">
	                </div>
	                <div class="range-div-dash">-</div>
	                <div class="range-div">                    
						<input type="text" name="to" id="to" class="input-small filter-input">
	                </div>
	                <div class="range-div">                    
						<span id="datepicker-clear" title="clear dates"></span>
	                </div>
	                 
                </div>              
                
			</div>
			<!--filter div end-->
                        
                <a href="#" id="csv-export" class="btn btn-warning csv">CSV</a>
		<!-- <a href="#" class="btn btn-warning chart" id="chart">Chart</a> -->
                    
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
				<th width="0%">id</th>
				<th><?php echo $this->lang->line('feedback_name');?></th>
                <th><?php echo $this->lang->line('feedback_comments');?></th>
				<th><?php echo $this->lang->line('feedback_email');?></th>	                                
				<th><?php echo $this->lang->line('feedback_date');?></th>	                                
				<th width="5%">Actions</th>
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
    
var bookId = $('#book_id').val();
var deviceType = $('#device_type').val();
var planTypeId = $('#plan_type_id').val();
var year = $('#year').val();
var from = ($('#from').val() != '')?$('#from').val():'null';
var to = ($('#to').val() != '')?$('#to').val():'null';

//datepicker generator
function initDatePicker()
{	
	year = $('#year').val();
	var dateForDatePicker = new Date();
	dateForDatePicker.setFullYear(year);
		
	$( "#from, #to" ).datepicker( "destroy" );
	$( "#from" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			changeYear:false,
			defaultDate: dateForDatePicker,
			dateFormat: "<?php echo DATE_FORMAT_DATEPICKER;?>",
			onClose: function( selectedDate ) {      	
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		$( "#to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			changeYear:false,
			defaultDate: dateForDatePicker,
			dateFormat: "<?php echo DATE_FORMAT_DATEPICKER;?>",
			onClose: function( selectedDate ) {
			$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
	});			
}

//reload table grid with csv link regenerate
function reloadList()
{
	bookId = $('#book_id').val();
	deviceType = $('#device_type').val();
	planTypeId = $('#plan_type_id').val();
	year = $('#year').val();
	from = ($('#from').val() != '')?$('#from').val():'null';
	to = ($('#to').val() != '')?$('#to').val():'null';

	if(from != 'null' && to != 'null') 
	{			
		from = $( "#from" ).datepicker( "option", "dateFormat", 'yy-mm-dd' ).val();		
		to = $( "#to" ).datepicker( "option", "dateFormat", 'yy-mm-dd' ).val();
		$( "#from, #to" ).datepicker( "option", "dateFormat", "<?php echo DATE_FORMAT_DATEPICKER; ?>" );
	}
	oTable.fnReloadAjax( '<?php echo base_url('feedback/get');?>'+'/bid/'+bookId+"/dtype/"+deviceType+"/ptypeid/"+planTypeId+"/year/"+year+"/from/"+from+"/to/"+to ); 	
}

//redirect to csv export link
function csvRedirect()
{
	bookId = $('#book_id').val();
	deviceType = $('#device_type').val();
	planTypeId = $('#plan_type_id').val();
	year = $('#year').val();
	from = ($('#from').val() != '')?$('#from').val():'null';
	to = ($('#to').val() != '')?$('#to').val():'null';
	
	if(from != 'null' && to != 'null') 
	{			
		from = $( "#from" ).datepicker( "option", "dateFormat", 'yy-mm-dd' ).val();		
		to = $( "#to" ).datepicker( "option", "dateFormat", 'yy-mm-dd' ).val();
		$( "#from, #to" ).datepicker( "option", "dateFormat", "<?php echo DATE_FORMAT_DATEPICKER; ?>" );
	}
	
	window.location.href = "<?php echo base_url('feedback/exportCsv')?>"+"/bid/"+bookId+"/dtype/"+deviceType+"/ptypeid/"+planTypeId+"/year/"+year+"/from/"+from+"/to/"+to;
}

$(document).ready(function () {
	
	oTable = $('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?php echo base_url('feedback/get');?>/bid/null/dtype/null/ptypeid/null/year/<?php echo date('Y'); ?>/from/null/to/null",
		"sPaginationType": "full_numbers",
		 "aaSorting" : [[0, 'desc']],
		"aoColumns": [
            {"bVisible":false},
            null,
            null,
            null,
            null,
            { "mDataProp": function( data, type, val ) {
            	return '<a class="btn btn-mini btn-info" href="<?php echo base_url(); ?>feedback/view/'+data[0]+'"><i title="View" data-rel="tooltip" data-rel="tooltip" class="icon-list icon-white"></i></a>';
            },"bSortable":false,"sClass": "pagination-centered" }
        ]
	} );
        
        //filter input change
	$('#book_id, #device_type, #plan_type_id').change( function() { 		 					
		reloadList();
	} );
	
	//range input change
	$('#from, #to').change( function() {		
		from = ($('#from').val() != '')?$('#from').val():'null';
		to = ($('#to').val() != '')?$('#to').val():'null';
		if(from != 'null' && to != 'null')
		reloadList();
	} );
	
	//year change
	$('#year').change( function() {
		$( "#from, #to" ).val("");		
		initDatePicker();				
		reloadList();
	} );    
    
	
	//datepicker clear
	$('#datepicker-clear').click(function() {
		$( "#from, #to" ).val("");		
		reloadList();
	});
	
	$('#csv-export').click(function() {
    	csvRedirect();	
	});
	
	//initialize datepicker
	initDatePicker();
});
</script>
