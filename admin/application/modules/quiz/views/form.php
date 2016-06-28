<?php
/**
	 * @name: index.php
	 * 
	 * @desc: quiz  main listing add/edit file for admin
	 * 
	 * @author: Pratyush Dimri
	 */
?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('quiz');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_quiz'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_quiz'); ?>
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
						<div class="form-label"><label class="control-label" for="book_id"><?php echo $this->lang->line('quiz_book');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="book_id" name="book_id" data-rel="chosen">
								<option value="0" <?php if (isset($query->book_id) && $query->book_id == 0):?>selected="selected"<?php endif; ?> <?php echo set_select('book_id',0); ?>>General Quiz</option>
								<?php foreach ($magazines as $magazine): ?>
								<option value="<?php echo $magazine->id;?>" <?php if (isset($query->book_id) && $query->book_id == $magazine->id):?>selected="selected"<?php endif; ?> <?php echo set_select('book_id',$magazine->id); ?>><?php echo $magazine->title;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>                                      					
					   
                    <div class="control-group">
					<div class="form-label"><label class="control-label" for="status"><?php echo $this->lang->line('plan_status');?></label><span class="asterisk">&nbsp;*</span></div>
					<div class="controls">
						<select id="status" name="status" data-rel="chosen">
						<option value="active" <?php if (isset($query->status) && $query->status == 'active'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Active</option>
						<option value="inactive" <?php if (isset($query->status) && $query->status == 'inactive'):?>selected="selected"<?php endif; ?> <?php echo set_select('status'); ?>>Inactive</option>							
						</select>
					</div>
					</div>                                                
                      
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>quiz/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->