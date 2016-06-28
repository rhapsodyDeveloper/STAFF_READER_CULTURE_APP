<?php
/**
	 * @name: index.php
	 * 
	 * @desc: reports listing view file for admim
	 * 
	 * @author: Pratyush Dimri
	 */
/*
$cntSql = "SELECT count(books.title) as count
		FROM   orders
		LEFT JOIN users ON users.id = orders.user_id LEFT JOIN books 
ON books.id = orders.book_id LEFT JOIN plan_types 
ON plan_types.id = books.plan_type_id LEFT JOIN devices 
ON (devices.user_id = orders.user_id AND devices.device_code = orders.device_code) 
		WHERE DATE_FORMAT(orders.created,'%Y') = ".date('Y');
echo $cntSql; exit;

$cnt = $this->db->query("select count(id) as cnt from users");
$cnt = $cnt->result();
$cntRow = $cnt[0]->cnt;*/


?>
<link href="<?php echo admin_asset_url();?>css/datatable.css" rel="stylesheet">
<link href="<?php echo admin_asset_url();?>css/jquery.fancybox-1.3.4.css" rel="stylesheet">
<script src='<?php echo admin_asset_url();?>js/reloadajax.js'></script>
<script src='<?php echo admin_asset_url();?>js/ZeroClipboard.js'></script>
<script type="text/javascript" src='<?php echo admin_asset_url();?>js/jquery.fancybox-1.3.4.pack.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div class="row-fluid">		
<div class="box-header well" data-original-title>
	<h2><i class="icon-file"></i> <?php echo $this->lang->line('reports'); ?></h2>			
</div>
	<div id="container">	
            <div id="count" class="count" style="float: right; margin: 3px 0px 3px 0px;"></div>
		<div id="dynamic">
			<!--filter div start-->
			<div class="filter-div form-horizontal">
			
				<div class="filter-by">
				
					<div class="filter-by-div">Filter By</div>					
														
					<div class="filter-by-div">									
						<select id="book_id" name="book_id" data-rel="chosen">
							<option value="null">All Books</option>
							<?php foreach ($books as $value) {
								echo "<option value='$value->id'>$value->title</option>";
							}?>
						</select>				
					</div>
					
					
					<div class="filter-by-div">
						<select id="device_type" name="device_type" data-rel="chosen">
							<option value="null">All Device Types</option>
							<?php foreach ($deviceTypes as $value) {
								echo "<option value='$value->device_type'>$value->device_type</option>";
							}?>
						</select>
					</div>
					
					
					<div class="filter-by-div">			
						<select id="plan_type_id" name="plan_type_id" data-rel="chosen">
							<option value="null">All Book Types</option>
							<?php foreach ($planTypesId as $value) {
								echo "<option value='$value->id'>$value->name</option>";
							}?>
						</select>
					</div>			
				</div>
				
				<div class="range">
					
					<div class="range-div-title">Range</div>				
					<div class="range-div">					
						<select id="year" name="year" data-rel="chosen">
							<?php foreach ($year as $value) {								
								$selected = ($value->year == date('Y'))?"selected='selected'":'';
								echo "<option value='$value->year' $selected>$value->year</option>";
							}?>
						</select>
					</div>
					<div class="range-div">                	
						<input type="text" name="from" id="from" class="input-small">
	                </div>
	                <div class="range-div-dash">-</div>
	                <div class="range-div">                    
						<input type="text" name="to" id="to" class="input-small">
	                </div>
	                <div class="range-div">                    
						<span id="datepicker-clear" title="clear dates"></span>
	                </div>
	                 
                </div>          	
                
			</div>
			<!--filter div end-->	
			<a href="#" id="csv-export" class="btn btn-warning csv">CSV</a>
			<a href="#" class="btn btn-warning chart" id="chart">Chart</a>			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>				
				<th  width="15%"><?php echo $this->lang->line('reports_name');?></th>				
				<th  width="15%"><?php echo $this->lang->line('reports_email');?></th>				
				<th  width="20%"><?php echo $this->lang->line('reports_book');?></th>				
				<th  width="15%"><?php echo $this->lang->line('reports_plan_type');?></th>				
				<th  width="15%"><?php echo $this->lang->line('reports_date');?></th>				
				<th  width="10%"><?php echo $this->lang->line('reports_device_type');?></th>
				<th  width="10%"><?php echo $this->lang->line('reports_status');?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="dataTables_empty">Loading data from server</td>
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
	oTable.fnReloadAjax( "<?php echo base_url('reports/getReport');?>"+"/bid/"+bookId+"/dtype/"+deviceType+"/ptypeid/"+planTypeId+"/year/"+year+"/from/"+from+"/to/"+to );	
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
	
	window.location.href = "<?php echo base_url('reports/exportCsv')?>"+"/bid/"+bookId+"/dtype/"+deviceType+"/ptypeid/"+planTypeId+"/year/"+year+"/from/"+from+"/to/"+to;
}

$(document).ready(function () {
	
	//table grid init			
	oTable = $('#example').dataTable( {		
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?php echo base_url('reports/getReport');?>"+"/bid/"+bookId+"/dtype/"+deviceType+"/ptypeid/"+planTypeId+"/year/"+year+"/from/"+from+"/to/"+to,		
		"sPaginationType": "full_numbers",		
		"aoColumns": [                     
            null,                      
            null,            
            null,            
            null,            
            null,            
            null,            
            null            
        ]
	} );
        
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('reports/getReport');?>/bid/null/dtype/null/ptypeid/null/year/<?php echo date('Y'); ?>/from/null/to/null",
            data: {  }
        })
        .done(function( msg ) {
            var json = JSON.parse(msg);
            var cnt = json["iTotalDisplayRecords"];
            $('#count').html("Total Row: "+cnt)
        });
		
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
    
    //Chart generation
    $('#chart').click(function() {
    bookId = $('#book_id').val();
    year = $('#year').val();
    deviceType = $('#device_type').val();
    planTypeId = $('#plan_type_id').val();
    
    $this = $(this);
    $.fancybox({
				'width'				: 950,
				'height'			: 650,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				'href'				: '<?php echo base_url('reports/bookChart')?>'+'/'+bookId+'/'+year+'/'+deviceType+'/'+planTypeId
			});
    	return false;
	});
    
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