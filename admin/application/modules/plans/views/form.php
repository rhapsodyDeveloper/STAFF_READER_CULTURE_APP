<!-- Plan Management form page for add/edit-->
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('plans');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_plans'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_plans'); ?>
					<?php endif; ?>
					</legend>
					<?php if(validation_errors()){?>
					<div class="alert alert-error">
						<ul>
							<?php echo validation_errors(); ?>
						</ul>
					</div>
					<?php } ?>
					<div class="control-group">
                        	<div class="form-label"><label class="control-label" for="title"><?php echo $this->lang->line('plan_title');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="title" id="title" value="<?php echo (isset($query->title))?$query->title:'';?><?php echo set_value('title'); ?>"  class="input-xlarge">
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="price"><?php echo $this->lang->line('price');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="price" id="price" value="<?php echo (isset($query->price))?$query->price:'';?><?php echo set_value('price'); ?>"  class="input-xlarge" maxlength="5">
                            </div>
                        </div>
                                                
                        <div class="control-group"  style="display:none;">
                        	<div class="form-label"><label class="control-label" for="currency"><?php echo $this->lang->line('currency');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="currency" id="currency" value="<?php echo (isset($query->currency))?$query->currency:set_value('currency','USD');?>"  class="input-xlarge">
                            </div>
                        </div>                                              	
                        
                        <div class="control-group"  style="display:none;">
						<div class="form-label"><label class="control-label" for="plan_type_name"><?php echo $this->lang->line('plan_type_name');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="plan_type_id" name="plan_type_id" data-rel="chosen">
								<?php foreach ($planType as $row): ?>
								<option value="<?php echo $row->id;?>" <?php if (isset($query->plan_type_id) && $query->plan_type_id == $row->id):?>selected="selected"<?php endif; ?> <?php echo set_select('plan_type_id',$row->id); ?>><?php echo $row->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						</div>
                        
                        <div class="control-group"  style="display:none;">
                        	<div class="form-label"><label class="control-label" for="duration_number"><?php echo $this->lang->line('duration_number');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="duration_number" id="duration_number" value="<?php echo (isset($query->duration_number))?$query->duration_number:'';?><?php echo set_value('duration_number'); ?>"  class="input-xlarge">
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="apple_product_id"><?php echo $this->lang->line('plan_apple_product_code');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="apple_product_id" id="apple_product_id" value="<?php echo (isset($query->apple_product_id))?$query->apple_product_id:'';?><?php echo set_value('apple_product_id'); ?>"  class="input-xlarge">
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="google_product_id"><?php echo $this->lang->line('plan_google_product_code');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="google_product_id" id="google_product_id" value="<?php echo (isset($query->google_product_id))?$query->google_product_id:'';?><?php echo set_value('google_product_id'); ?>"  class="input-xlarge">
                            </div>
                        </div>
                      
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>plans/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->