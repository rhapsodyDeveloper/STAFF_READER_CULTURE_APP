<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Slider main listing add/edit file for admin
	 * 
	 * @author: Pratyush Dimri
	 */
?>
<?php if($this->session->flashdata('info')){?>
<div class="alert alert-success">
<?php echo $this->session->flashdata('info');?>
</div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
<div class="alert alert-error">
<?php echo $this->session->flashdata('error');?>
</div>
<?php } ?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('slider');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open_multipart('','class="form-horizontal"'); ?>
				<fieldset> 
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_slider'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_slider'); ?>
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
                        	<div class="form-label"><label class="control-label" for="name"><?php echo $this->lang->line('slider_name');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="name" id="name" value="<?php echo (isset($query->name))?$query->name:'';?><?php echo set_value('name'); ?>"  class="input-xlarge">
                            </div>
                        </div>

                        <div class="control-group" id="thumb_image_lbl">
                        	<div class="form-label"><span class="control-label" for="image"><?php echo $this->lang->line('slider_image');?></span><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="file" name="image" id="image" class="input-xlarge">
                            </div>
                             <?php if($this->uri->segment(2) == 'edit') {?>
                             <input type="hidden" name="current_image" value="<?php echo (isset($query->image))?$query->image:set_value('current_image');?>">
                            <div class="form-label"><label class="control-label"></label></div>
                            <div class="controls">	
                            	<img height="100px" width="100px" src="<?php echo base_url('assets/slider/').'/';echo (isset($query->image))?$query->image:set_value('current_path');?>" />
                            </div>
                            <? }?>
                        </div>                      	
                        
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="status"><?php echo $this->lang->line('slider_status');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="status" name="status" data-rel="chosen">
							<option value="Active" <?php if (isset($query->status) && $query->status == 'Active'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Active</option>
							<option value="Inactive" <?php if (isset($query->status) && $query->status == 'Inactive'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Inactive</option>							
							</select>
						</div>
						</div>                                                
                      
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>slider/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->