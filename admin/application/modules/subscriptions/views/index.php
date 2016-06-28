<?php
                              

/**
 * @name: index.php
 * 
 * @desc: order subscriptions    main listing view file for admin
 * 
 * @author: Ravindra Shekhawat
 */
?>

<?php if ($this->session->flashdata('info')) { ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('info') ?>
    </div>
<?php } ?>
<?php if ($this->session->flashdata('error')) { ?>
    <div class="alert alert-error">
        <?php echo $this->session->flashdata('error') ?>
    </div>
<?php } ?>

<link href="<?php echo admin_asset_url(); ?>css/datatable.css" rel="stylesheet">
<script src='<?php echo admin_asset_url(); ?>js/reloadajax.js'></script>
<div class="row-fluid">		
    <div class="box-header well" data-original-title>
        <h2><i class="icon-envelope"></i> <?php echo $this->lang->line('subscriptions'); ?></h2>
        <div class="box-icon">		
            <a class="btn btn-primary" id="csv-export" href="#"><i class=" icon-download-alt icon-white"></i></a> 
        </div>	 
    </div>
    <div id="container">				
        <div id="dynamic">
            <?php if (!isset($_REQUEST['orderId'])) { ?>
                <!--filter div start-->
                <div class="filter-div form-horizontal">


                    <div class="range">
                        <div class="range-div-title">Plan Type</div>
                        <div class="range-div">
                          
                            <select id="plan_type_id" name="plan_type_id" data-rel="chosen">
                            <?php foreach ($plan_title as $row){ ?>
                                <option value="<?php echo $row->id; ?>">[<?php echo $row->plan_flag; ?>]<?php echo $row->title; ?></option>
                            <?php } ?>
                        </select>
                          
                        </div>
                        <div class="range-div-title">Year</div>
                        <div class="range-div">					
                            <?= yearDropdown(2002, Date('Y')); ?>
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

            <?php } ?>     
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>id</th>
                        <th><?php echo $this->lang->line('order_number'); ?></th>
                        <th><?php echo $this->lang->line('subs_date'); ?></th>
                        <th><?php echo $this->lang->line('expiry_date'); ?></th>
                        <th><?php echo $this->lang->line('user_email'); ?></th>
                        <th><?php echo $this->lang->line('plan_title'); ?></th>
                        <th><?php echo $this->lang->line('content_title'); ?></th>
                        <th><?php echo $this->lang->line('payment_status'); ?></th>
                        <th>Actions</th>
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
    var year = $('#year').val();
    var from = ($('#from').val() != '') ? $('#from').val() : 'null';
    var to = ($('#to').val() != '') ? $('#to').val() : 'null';

    var orderId = 'null';
    <?php if (isset($_REQUEST['orderId'])) { ?>  
        
        orderId = "<?php echo $_REQUEST['orderId']; ?>";
    
    <?php } ?>

//datepicker generator
    function initDatePicker(){
        year = $('#year').val();
        var dateForDatePicker = new Date();
        dateForDatePicker.setFullYear(year);

        $("#from, #to").datepicker("destroy");
        
        $("#from").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: false,
            defaultDate: dateForDatePicker,
                    dateFormat: "<?php echo DATE_FORMAT_DATEPICKER; ?>",
            onClose: function(selectedDate) {
                $("#to").datepicker("option", "minDate", selectedDate);
            }
        });
        
        $("#to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: false,
            defaultDate: dateForDatePicker,
                    dateFormat: "<?php echo DATE_FORMAT_DATEPICKER; ?>",
            onClose: function(selectedDate) {
                $("#from").datepicker("option", "maxDate", selectedDate);
            }
        });
    }

    //reload table grid with csv link regenerate
    function reloadList(){
        year = $('#year').val();
        from = ($('#from').val() != '') ? $('#from').val() : 'null';
        to = ($('#to').val() != '') ? $('#to').val() : 'null';
        plan_type_id = ($('#plan_type_id').val() != '') ? $('#plan_type_id').val() : 'null';

        if (from != 'null' && to != 'null'){
            from = $("#from").datepicker("option", "dateFormat", 'yy-mm-dd').val();
            to = $("#to").datepicker("option", "dateFormat", 'yy-mm-dd').val();
            $("#from, #to").datepicker("option", "dateFormat", "<?php echo DATE_FORMAT_DATEPICKER; ?>");
        }
        oTable.fnReloadAjax('<?php echo base_url('subscriptions/get'); ?>' + "/year/" + year + "/from/" + from + "/to/" + to + "/orderId/" + orderId+ "/plan_type_id/" + plan_type_id);
    }

    //redirect to csv export link
    function csvRedirect(){
       // window.location.href = "<?php echo base_url('subscriptions/exportCsv') ?>"+"/?year=" + year + "&from=" + from + "&to=" + to + "&=orderId=" + orderId";
        
        year = $('#year').val();
        from = ($('#from').val() != '') ? $('#from').val() : 'null';
        to = ($('#to').val() != '') ? $('#to').val() : 'null';
        plan_type_id = ($('#plan_type_id').val() != '') ? $('#plan_type_id').val() : 'null';

        if (from != 'null' && to != 'null'){
            from = $("#from").datepicker("option", "dateFormat", 'yy-mm-dd').val();
            to = $("#to").datepicker("option", "dateFormat", 'yy-mm-dd').val();
            $("#from, #to").datepicker("option", "dateFormat", "<?php echo DATE_FORMAT_DATEPICKER; ?>");
        }
     window.location.href = "<?php echo base_url('subscriptions/exportCsv') ?>"+"/?year=" + year + "&from=" + from + "&to=" + to + "&orderId=" + orderId +"&plan_type_id=" + plan_type_id;
    }
    
    //Load defualt with Ajax Request list down 
    $(document).ready(function() {
        oTable = $('#example').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('subscriptions/get'); ?>/year/<?php echo date('Y');?>/from/null/to/null/plan_type_id/null/orderId/" + orderId,
            "sPaginationType": "bootstrap",
            "aaSorting": [[0, 'desc']],
            "aoColumns": [
                {"bVisible": false},
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                {"mDataProp": function(data, type, val) {
                        return '<a class="btn btn-mini btn-info" href="<?php echo base_url(); ?>subscriptions/edit/' + data[0] + '"><i title="Edit" data-rel="tooltip" class="icon-edit icon-white"></i></a> <a class="btn btn-mini btn-danger" href="<?php echo base_url(); ?>orders/delete/' + data[0] + '"><i title="Delete" data-rel="tooltip" class="icon-trash icon-white"></i></a>';
                    }, "bSortable": false}
            ]
        });
        
        //Delete click event
        $(".btn-danger").live('click', function() {
            if (confirm("Are you sure you want to delete this data?"))
                location.href = "<?php echo base_url(); ?>subscriptions/delete/" + this.id;
            else
                return false;
        });
        
        //Export CSV Click
        $('#csv-export').click(function() {
            csvRedirect();
        });
        //range input change
        $('#from, #to').change(function() {
            from = ($('#from').val() != '') ? $('#from').val() : 'null';
            to = ($('#to').val() != '') ? $('#to').val() : 'null';
            if (from != 'null' && to != 'null')
                reloadList();
        });

        //year change
        $('#year').change(function() {
            $("#from, #to").val("");
            initDatePicker();
            reloadList();
        });
        
        $('#plan_type_id').change(function() {
           // $("#from, #to").val("");
            initDatePicker();
            reloadList();
        });


        //datepicker clear
        $('#datepicker-clear').click(function() {
            $("#from, #to").val("");
            reloadList();
        });
        
        //initialize Timepiker 
        initDatePicker();
    });
</script>