<?php
/**
	 * @name: index.php
	 * 
	 * @desc: quiz question main listing add/edit file for admin
	 * 
	 * @author: Pratyush Dimri
	 */
?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('quiz_question');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open('','class="form-horizontal"'); ?>
			<input type="hidden" name="quiz_id" value="<?php echo $quizId;?>">
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_quiz_question'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_quiz_question'); ?>
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
						<div class="form-label"><label class="control-label" for="question"><?php echo $this->lang->line('quiz_question');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">	
							<input type="text" name="question" id="question" value="<?php echo (isset($query->question))?$query->question:'';?><?php echo set_value('question'); ?>"  class="input-xxlarge">							
						</div>
                    </div>
					
					<div class="control-group">
						<div class="form-label"><label class="control-label" for="option1"><?php echo $this->lang->line('quiz_option1');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">	
							<input type="text" name="option1" id="option1" value="<?php echo (isset($query->option1))?$query->option1:'';?><?php echo set_value('option1'); ?>"  class="input-small">							
						</div>
                    </div>
                    
                    <div class="control-group">
						<div class="form-label"><label class="control-label" for="option1"><?php echo $this->lang->line('quiz_option2');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">								
							<input type="text" name="option2" id="option2" value="<?php echo (isset($query->option2))?$query->option2:'';?><?php echo set_value('option2'); ?>"  class="input-small">							
						</div>
                    </div>
                    
                    <div class="control-group">
						<div class="form-label"><label class="control-label" for="option1"><?php echo $this->lang->line('quiz_option3');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">								
							<input type="text" name="option3" id="option3" value="<?php echo (isset($query->option3))?$query->option3:'';?><?php echo set_value('option3'); ?>"  class="input-small">							
						</div>
                    </div>
                    
                    <div class="control-group">
						<div class="form-label"><label class="control-label" for="option1"><?php echo $this->lang->line('quiz_option4');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">								
							<input type="text" name="option4" id="option4" value="<?php echo (isset($query->option4))?$query->option4:'';?><?php echo set_value('option4'); ?>"  class="input-small">
						</div>
                    </div> 

                    <div class="control-group">
						<div class="form-label"><label class="control-label" for="answer_type"><?php echo $this->lang->line('quiz_answer_type');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">	
							<select id="answer_type" name="answer_type">
								<option value="radio" <?php if (isset($query->answer_type) && $query->answer_type == 'radio'):?>selected="selected"<?php endif; ?> <?php echo set_select('answer_type'); ?>>Radio</option>
								<option value="checkbox" <?php if (isset($query->answer_type) && $query->answer_type == 'checkbox'):?>selected="selected"<?php endif; ?> <?php echo set_select('answer_type'); ?>>Checkbox</option>							
							</select>
						</div>
                    </div>                   
                    
					<div class="control-group">
						<div class="form-label"><label class="control-label"><?php echo $this->lang->line('quiz_answer');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">							
							<label class="checkbox inline">
							<div class="checker" id="uniform-inlineCheckbox1"><span><input type="checkbox" value="option1" id="inlineCheckbox1" name="answer[]" style="opacity: 0;" <?php if (isset($answers) && in_array('option1',$answers)):?>checked="checked"<?php endif; ?> <?php echo set_checkbox('answer[]','option1'); ?>></span></div> Option 1
							</label>
							<label class="checkbox inline">
							<div class="checker" id="uniform-inlineCheckbox2"><span><input type="checkbox" value="option2" id="inlineCheckbox2" name="answer[]" style="opacity: 0;" <?php if (isset($answers) && in_array('option2',$answers)):?>checked="checked"<?php endif; ?> <?php echo set_checkbox('answer[]','option2'); ?>></span></div> Option 2
							</label>
							<label class="checkbox inline">
							<div class="checker" id="uniform-inlineCheckbox3"><span><input type="checkbox" value="option3" id="inlineCheckbox3" name="answer[]" style="opacity: 0;" <?php if (isset($answers) && in_array('option3',$answers)):?>checked="checked"<?php endif; ?> <?php echo set_checkbox('answer[]','option3'); ?>></span></div> Option 3
							</label>
							<label class="checkbox inline">
							<div class="checker" id="uniform-inlineCheckbox4"><span><input type="checkbox" value="option4" id="inlineCheckbox4" name="answer[]" style="opacity: 0;" <?php if (isset($answers) && in_array('option4',$answers)):?>checked="checked"<?php endif; ?> <?php echo set_checkbox('answer[]','option4'); ?>></span></div> Option 4
							</label>
						</div>
					</div>                                          
                      
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>quizquestion/<?php echo $quizId;?>" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->