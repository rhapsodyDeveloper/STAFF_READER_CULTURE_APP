<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Plan type  main listing add/edit file for admin
	 * 
	 * @author: Pratyush Dimiri
	 */
?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('plan_type');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_plan_type'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_plan_type'); ?>
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
                        	<div class="form-label"><label class="control-label" for="name"><?php echo $this->lang->line('plan_type_name');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="name" id="name" value="<?php echo (isset($query->name))?$query->name:'';?><?php echo set_value('name'); ?>"  class="input-xlarge">
                            </div>
                        </div>                                                                                           	
                        
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="status"><?php echo $this->lang->line('plan_status');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="status" name="status" data-rel="chosen">
							<option value="Active" <?php if (isset($query->status) && $query->status == 'Active'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Active</option>
							<option value="Inactive" <?php if (isset($query->status) && $query->status == 'Inactive'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Inactive</option>							
							</select>
						</div>
						</div>                                                
                      
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>plantype/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->