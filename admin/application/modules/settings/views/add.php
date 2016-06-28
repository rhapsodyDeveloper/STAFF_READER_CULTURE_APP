<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
                    <h2><i class="icon-edit"></i> <?php echo $this->lang->line('settings_menu');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<?php if($this->session->flashdata('info')){?>
					<div class="alert alert-success">
						<?php echo $this->session->flashdata('info')?>
					</div>
					<?php } ?>
					<?php if(validation_errors()){?>
					<div class="alert alert-error">
						<ul>
							<?php echo validation_errors(); ?>
						</ul>
					</div>
					<?php } ?>
                                        <div class="control-group">
                                                <div class="form-label"><label class="control-label" for="key"><?php echo $this->lang->line('key');?></label><span class="asterisk">&nbsp;*</span></div>
                                            <div class="controls">	
                                                <input type="text" name="key" id="key" value="<?php echo (isset($query->key))?$query->key:'';?><?php echo set_value('key'); ?>"  class="input-xlarge">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                                <div class="form-label"><label class="control-label" for="value"><?php echo $this->lang->line('value');?></label><span class="asterisk">&nbsp;*</span></div>
                                            <div class="controls">	
                                                <input type="text" name="value" id="value" value="<?php echo (isset($query->value))?$query->value:'';?><?php echo set_value('value'); ?>"  class="input-xlarge">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                                <div class="form-label"><label class="control-label" for="label"><?php echo $this->lang->line('label');?></label><span class="asterisk">&nbsp;*</span></div>
                                            <div class="controls">	
                                                <input type="text" name="label" id="label" value="<?php echo (isset($query->label))?$query->label:'';?><?php echo set_value('label'); ?>"  class="input-xlarge">
                                            </div>
                                        </div>         
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>settings/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->