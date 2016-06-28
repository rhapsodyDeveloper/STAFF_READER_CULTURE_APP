<!-- Feedback Management form page for add/edit-->
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('feedback');?></h2>
		</div>
		

		<div class="box-content">
				<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>				
					<div class="control-group">
                        	<div class="form-label"><label class="control-label" for="name"><?php echo $this->lang->line('feedback_name');?></label></div>
                            <div class="controls">	
                            	<input type="text" value="<?php echo $query->name?>"  class="input-xlarge"  disabled>
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="price"><?php echo $this->lang->line('feedback_email');?></label></div>
                            <div class="controls">	
                            	<input type="text" value="<?php echo $query->email?>"  class="input-xlarge" disabled>
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="comments"><?php echo $this->lang->line('feedback_comments');?></label></div>
                            <div class="controls">	
                            	<textarea class="input-xlarge" disabled><?php echo $query->comments;?></textarea>	                            	
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="price"><?php echo $this->lang->line('feedback_date');?></label></div>
                            <div class="controls">	
                            	<input type="text" value="<?php echo date('d/m/Y',strtotime($query->created));?>"  class="input-xlarge" disabled>
                            </div>
                        </div>
                        
                        <div class="form-actions">						
						<a href="<?php echo base_url(); ?>feedback/" class="btn">Back</a>
					</div>
				</fieldset>
				<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->