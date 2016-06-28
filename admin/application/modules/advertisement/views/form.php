<?php
/**
 * @name: index.php
 * 
 * @desc: advertisement  main add/edit file for admin
 * 
 * @author: Pratyush Dimri
 */
if($this->session->flashdata('info')){?>
<div class="alert alert-success">
<?php echo $this->session->flashdata('info');?>
</div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
<div class="alert alert-error">
<?php echo $this->session->flashdata('error');?>
</div>
<?php }
if(isset($fileError)){
?>
<div class="alert alert-error">
<?php echo $fileError;?>
</div>
<?php }?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('advertisement');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open_multipart('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_advertisement'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_advertisement'); ?>
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
                        	<div class="form-label"><label class="control-label" for="title"><?php echo $this->lang->line('advertisement_title');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="title" id="title" value="<?php echo (isset($query->title))?$query->title:'';?><?php echo set_value('title'); ?>"  class="input-xlarge">
                            </div>
                        </div>
                        					
                       <div class="control-group">
						<div class="form-label"><label class="control-label" for="plan"><?php echo $this->lang->line('advertisement_plan');?></label><span class="asterisk">&nbsp;*</span></div>						
						<div class="controls">
							<select id="plan_id" name="plan_id" data-rel="chosen">
							<option value="0">All Plan</option>
								<?php foreach ($plan as $row): ?>
								<option value="<?php echo $row->id;?>" <?php if(isset($query->plan_id)&&($query->plan_id==$row->id)){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('plan_id',$row->id); ?>><?php echo $row->title;?></option>
								<?php endforeach; ?>
							</select>
							</div>
						</div>                                                   
                                                
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="country"><?php echo $this->lang->line('advertisement_country');?></label><span class="asterisk">&nbsp;*</span></div>						
						<div class="controls">
							<select id="country" name="country" data-rel="chosen">
							<option value="All Country">All Country</option>
								<?php foreach ($country as $row): ?>
								<option value="<?php echo $row->name;?>" <?php if(isset($query->country)&&($query->country==$row->name)){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('country',$row->name); ?>><?php echo $row->name;?></option>
								<?php endforeach; ?>
							</select>
							</div>
						</div>  

                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="device_type"><?php echo $this->lang->line('advertisement_device_type');?></label><span class="asterisk">&nbsp;*</span></div>						
						<div class="controls">
							<select id="device_type" name="device_type" data-rel="chosen">															<option value="All Device">All Device</option>
								<option value="iphone" <?php if(isset($query->device_type)&&($query->device_type=='iphone')){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('device_type','iphone'); ?>>iPhone</option>								
								<option value="android" <?php if(isset($query->device_type)&&($query->device_type=='android')){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('device_type','android'); ?>>Android</option>								
							</select>
							</div>
						</div> 
                        
                         <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="duration"><?php echo $this->lang->line('advertisement_duration');?></label><span class="asterisk">&nbsp;*</span></div>                         
                            <div class="controls">
                                <div class="input-prepend">                                      
                                    <input type="text" name="duration" id="duration" value="<?php echo (isset($query->duration))?$query->duration:'';?><?php echo set_value('duration'); ?>"  class="input-xmedium">
                                    <span class="add-on">sec</span>
                                </div>
                            </div>
                        </div>   

                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="link"><?php echo $this->lang->line('advertisement_link');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="link" id="link" value="<?php echo (isset($query->link))?$query->link:'';?><?php echo set_value('link'); ?>"  class="input-large">
                            </div>
                        </div>                       
                        
                        <div class="control-group" id="thumb_image_lbl">
                        	<div class="form-label"><span class="control-label" for="image_path"><?php echo $this->lang->line('advertisement_image');?></span><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="file" name="image_path" id="image_path" class="input-xlarge">
                            </div>
                             <?php if($this->uri->segment(2) == 'edit') {
                             	//echo $query->image_path;
                             	?>
                             <input type="hidden" name="current_image_path" value="<?php echo (isset($query->image_path))?$query->image_path:set_value('current_image_path');?>">
                            <div class="form-label"><label class="control-label"></label></div>
                            <div class="controls">	
                            	<img height="100px" width="200px" src="<?php echo base_url('assets/advertisement/').'/';echo (isset($query->image_path))?$query->image_path:set_value('current_image_path');?>" />
                            </div>
                            <? }?>
                        </div> 
						
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="status"><?php echo $this->lang->line('advertisement_status');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="status" name="status" data-rel="chosen">
								<?php foreach ($status as $row): ?>
								<option value="<?php echo $row;?>" <?php if(isset($query->status)&&($query->status==$row)){echo 'selected="selected"';}else {echo '';}?> <?php echo set_select('status',$row); ?>><?php echo $row;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>             											
						
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>advertisement/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->