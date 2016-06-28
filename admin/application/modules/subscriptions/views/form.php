<?php
/**
	 * @name: form.php(Order view/edit)
	 * 
	 * @desc: view/edit order subscription main listing view file for admin
	 * 
	 * @author: Ravindra Shekhawat
	 */
if(isset($query)){	
	$query->expiry_date = date(DATE_FORMAT,strtotime($query->expiry_date));	
	$query->subs_date = date(DATE_FORMAT,strtotime($query->subs_date));	
}
?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('subscriptions');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
						<?php echo $this->lang->line('edit_subscription'); ?>
                                            <input type="hidden" name="subscription_id" id="subscription_id"  value="<?php echo $query->subscription_id ;?>" >
					</legend>
                                    
                                        <!-- Message box for success/warning/error-->
					<?php if(validation_errors()){?>
					<div class="alert alert-error">
						<ul>
							<?php echo validation_errors(); ?>
                                                       
						</ul>
					</div>
					<?php } ?>
                                        
                                        <!--User Email   -->
					<div class="control-group">
						<label class="control-label" for="email"><?php echo $this->lang->line('email');?></label>
						<div class="controls">						
                                                    <input type="text" readonly="readonly"  id="email" name="email" value="<?php echo (isset($query->email))?$query->email:'';?><?php echo set_value('email'); ?>" class="input-xlarge">
						</div>
					</div>
                                        
                                        <!--Order Number -->
                                        <div class="control-group">
						<label class="control-label" for="order_number"><?php echo $this->lang->line('order_number');?></label>
						<div class="controls">						
							<input type="text" readonly="readonly" id="order_number" name="order_number" value="<?php echo (isset($query->order_number))?$query->order_number:'';?><?php echo set_value('order_number'); ?>" class="input-xlarge">
						</div>
					</div>
                                        <!-- Plan Title -->
					<div class="control-group">
						<label class="control-label" for="plan_title"><?php echo $this->lang->line('plan_title');?></label>				
						<div class="controls">
							<input type="text" readonly="readonly" id="plan_title" name="plan_title" value="<?php echo (isset($query->plan_title))?$query->plan_title:'';?>" class="input-xlarge">
						</div>
					</div>

					<!-- <div id="subscription_plan" class="subscription_plan" style="display:block;" >
                                          
                                            <div class="control-group" id="plan"  >
                                                    <label class="control-label" for="plan_title"><?php echo $this->lang->line('plan_title');?></label>				
                                                    <div class="controls">
                                                        <select id="plan_title" name="plan_title" data-rel="chosen">
                                                                        <?php foreach ($plan_title as $row): ?>
                                                                        <option value="<?php echo $row->id;?>" <?php echo (isset($query->plan_id) && $query->plan_id==$row->id)?'selected':''; ?>><?php echo $row->title;?></option>
                                                                        <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                            </div>
                                            <input type="hidden" name="plan_type_name" id="plan_type_name" value="1">
                                        </div>
                                         -->
                                        <!--Plan Type Name -->
						<div class="control-group">
						<label class="control-label" for="plan_type_name"><?php echo $this->lang->line('plan_type_name');?></label>
						<div class="controls">						
						<input type="text" readonly="readonly" id="plan_type_name" name="plan_type_name" value="<?php echo (isset($query->plan_type_name))?$query->plan_type_name:'';?><?php echo set_value('plan_type_name'); ?>" class="input-xlarge">
						</div>
						</div>
                                        
                                        <!--Subs Date -->
                                        <div class="control-group">
						<label class="control-label" for="subs_date"><?php echo $this->lang->line('subs_date');?></label>
						<div class="controls">						
							<input type="text" readonly="readonly" id="subs_date" name="subs_date" value="<?php echo (isset($query->subs_date))?$query->subs_date:'';?><?php echo set_value('subs_date'); ?>" class="input-xlarge">
						</div>
					</div>
                                        
                                        <!--Expiry Date -->
                                        <div class="control-group">
						<label class="control-label" for="expiry_date"><?php echo $this->lang->line('expiry_date');?></label>
						<div class="controls">						
                                                    <input type="text" id="expiry_date" class="datepicker" name="expiry_date" value="<?php echo (isset($query->expiry_date))?$query->expiry_date:'';?><?php echo set_value('expiry_date'); ?>" class="input-xlarge">
						</div>
					</div>
					
                                        <!--Content Title -->
					<div class="control-group">
						<label class="control-label" for="content_title"><?php echo $this->lang->line('content_title');?></label>
						<div class="controls">						
							<input type="text" readonly="readonly" id="content_title" name="content_title" value="<?php echo (isset($query->content_title))?$query->content_title:'';?><?php echo set_value('content_title'); ?>" class="input-xlarge">
						</div>
					</div>
                                        
                                         <!--Subscription Status -->
					 <div class="control-group">
						<label class="control-label" for="subscription_status"><?php echo $this->lang->line('subscription_status');?></label>
						<div class="controls">
							<select id="subscription_status" name="subscription_status" data-rel="chosen">
								<?php foreach ($status as $row): ?>
								<option value="<?php echo $row;?>" <?php if($query->subscription_status==$row){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('subscription_status',$row); ?>><?php echo $row;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
                                        
                                                                       
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>subscriptions" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->