
<?php
/**
	 * @name: index.php
	 * 
	 * @desc: Send Notification
	 * 
	 * @author: Kirti Valand
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
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('push_notification');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					
						<?php echo $this->lang->line('send_push_notification'); ?>
					
					</legend>
					<?php if(validation_errors()){?>
					<div class="alert alert-error">
						<ul>
							<?php echo validation_errors(); ?>
						</ul>
					</div>
					<?php } ?>
					<div class="control-group">
                        	<div class="form-label"><label class="control-label" for="title"><?php echo $this->lang->line('notification_device');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
							
							<?php 
							$data = array(
							'name'        => 'send_to[]',
							'id'          => 'send_to_ios',
							'value'       => 'ios',
							'checked'     => FALSE,
							);
							echo form_checkbox($data);
							?>IOS 
                           
							<?php 
							$data = array(
							'name'        => 'send_to[]',
							'id'          => 'send_to_android',
							'value'       => 'android',
							'checked'     => FALSE,
							);
							echo form_checkbox($data);
							?>	 Android 
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="price"><?php echo $this->lang->line('notification_message');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<textarea cols="100" rows="10" name="message" id="message"><?php echo (isset($query->message))?$query->message:'';?><?php echo set_value('message'); ?></textarea>


                            </div>
                        </div>
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>pushnotification/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->