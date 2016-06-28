<?php
/**
 * @name: index.php
 * 
 * @desc: Mobi App Store  main add/edit file for admin
 * 
 * @author: Pratyush Dimiri
 */
if($this->session->flashdata('error')){ ?>
<div class="alert alert-error">
<? foreach ($this->session->flashdata('error') as $value) { echo $value."<br>"; } ?>
</div>
<?php }?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header well" data-original-title>
			<h2><i class="icon-edit"></i> <?php echo $this->lang->line('mobi_app_store');?></h2>
		</div>
		

		<div class="box-content">
			<?php echo form_open_multipart('','class="form-horizontal"'); ?>
				<fieldset>
					<legend>
					<?php if ($this->uri->segment(2) != 'edit'): ?>
						<?php echo $this->lang->line('add_mobi_app_store'); ?>
					<?php else: ?>
						<?php echo $this->lang->line('edit_mobi_app_store'); ?>
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
                        	<div class="form-label"><label class="control-label" for="title"><?php echo $this->lang->line('mobi_app_store_title');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="title" id="title" value="<?php echo (isset($query['title']))?$query['title']:'';?><?php echo set_value('title'); ?>"  class="input-xlarge">
                            </div>
                        </div>

                       <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="description"><?php echo $this->lang->line('mobi_app_store_description');?></label></div>
                            <div class="controls">
                            	<textarea name="description" id="description" class="input-xlarge"><?php echo (isset($query['description']))?$query['description']:set_value('description');?></textarea>	                            	
                            </div>
                        </div>

                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="apple_product_code"><?php echo $this->lang->line('mobi_app_store_apple_product_code');?></label></div>
                            <div class="controls">	
                            	<input type="text" name="apple_product_id" id="apple_product_id" value="<?php echo (isset($query['apple_product_id']))?$query['apple_product_id']:'';?><?php echo set_value('apple_product_id'); ?>"  class="input-xlarge">
                            </div>
                        </div> 

                        <div class="control-group">
                        	<div class="form-label"><label class="control-label" for="google_product_code"><?php echo $this->lang->line('mobi_app_store_google_product_code');?></label></div>
                            <div class="controls">	
                            	<input type="text" name="google_product_id" id="google_product_id" value="<?php echo (isset($query['google_product_id']))?$query['google_product_id']:'';?><?php echo set_value('google_product_id'); ?>"  class="input-xlarge">
                            </div>
                        </div>                      	
                        
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="content_type"><?php echo $this->lang->line('mobi_app_store_content_type');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="content_type" name="content_type" data-rel="chosen">
								<option value="ebook" <?php if (isset($query['content_type']) && $query['content_type'] == 'ebook'):?>selected="selected"<?php endif; ?> <?php echo set_select('content_type','ebook'); ?>>Ebook (Only text)</option>
								<option value="audio" <?php if (isset($query['content_type']) && $query['content_type'] == 'audio'):?>selected="selected"<?php endif; ?> <?php echo set_select('content_type','audio'); ?>>Audio (Only audio)</option>
								<option value="combo" <?php if (isset($query['content_type']) && $query['content_type'] == 'combo'):?>selected="selected"<?php endif; ?> <?php echo set_select('content_type','combo'); ?>>Combo (Text with audio)</option>								
							</select>
						</div>
						</div>
						
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="book_type"><?php echo $this->lang->line('mobi_app_store_category');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="plan_type_id" name="plan_type_id" data-rel="chosen">
								<?php foreach ($planType as $row): ?>
								<option value="<?php echo $row->id;?>" <?php if (isset($query['plan_type_id']) && $query['plan_type_id'] == $row->id):?>selected="selected"<?php endif; ?> <?php echo set_select('plan_type_id',$row->id); ?>><?php echo $row->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						</div>
						
                        <div class="control-group" id="author-tab" style="diplay:none;">
						<div class="form-label"><label class="control-label" for="author"><?php echo $this->lang->line('mobi_app_store_author');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="author_id" name="author_id" data-rel="chosen">								
								<?php foreach ($author as $row): ?>
								<option value="<?php echo $row->id;?>" <?php if (isset($query['author_id']) && $query['author_id'] == $row->id):?>selected="selected"<?php endif; ?> <?php echo set_select('author_id',$row->id); ?>><?php echo $row->name;?></option>
								<?php endforeach; ?>
							</select>
						</div>
						</div>												
                        
						<div class="control-group">
                        	<div class="form-label"><label class="control-label" for="price"><?php echo $this->lang->line('mobi_app_store_price');?></label><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">
                                <div class="input-prepend">  
                                    <span class="add-on">$</span>
                                    <?php
                                    	$price = 0;
                                    	if(@$query['price'] != 0){
                                    		$price = $query['price'];
                                    	}elseif(@$query['combo_price'] != 0){
                                    		$price = $query['combo_price'];
                                    	}elseif(@$query['audio_price'] != 0){
                                    		$price = $query['audio_price'];
                                    	}
                                    ?>
                                    <input type="text" name="price" id="price" value="<?php echo $price;?><?php echo set_value('price'); ?>"  class="input-xmedium" maxlength="5">
                                </div>
                            </div>
                        </div>
                        
                        <div class="control-group">
                        	<div class="form-label"><span class="control-label" for="publish_date"><?php echo $this->lang->line('mobi_app_store_publish_date');?></span><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="text" name="publish_date" id="publish_date" class="datepicker input-large" value="<?php echo (isset($query['publish_date']) && ($query['publish_date'] !='') && ($query['publish_date'] !='01/01/1970')  )?$query['publish_date']:'';?><?php echo set_value('publish_date'); ?>" readonly="readonly" autocomplete="off" style="background-color:#FFF; cursor:text; border:1px solid #CCCCCC;">
                            </div>
                        </div>                                                                                               

                        <div class="control-group" id="ebook_lbl">
                        	<div class="form-label"><span class="control-label" for="ebook"><?php echo $this->lang->line('mobi_app_store_ebook');?></span><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="file" name="ebook_name" id="ebook_name" class="input-xlarge">                            	
                            </div>
                            <?php if($this->uri->segment(2) == 'edit') {
                            	$bookName = (isset($query['ebook_name']) && $query['ebook_name'] != '')?$query['ebook_name']:set_value('current_ebook_name');
                            	?>
                            <input type="hidden" name="current_ebook_name" value="<?php echo $bookName;?>">
                            <div class="form-label"><label class="control-label"></label></div>
                            <div class="controls">
                            	<a href="<?php echo BOOK_URL.$this->uri->segment(3).'/'.$bookName;?>"><?php echo $bookName;?></a>
                            </div>
                            <? }?>
                        </div>                       
                        
                        <div class="control-group" id="thumb_image_lbl">
                        	<div class="form-label"><span class="control-label" for="thumb_image"><?php echo $this->lang->line('mobi_app_store_thumb_image');?></span><span class="asterisk">&nbsp;*</span></div>
                            <div class="controls">	
                            	<input type="file" name="thumb_image" id="thumb_image" class="input-xlarge">
                            </div>
                             <?php if($this->uri->segment(2) == 'edit') {
                             	$imageName = (isset($query['thumb_image']) && $query['thumb_image'] != '')?$query['thumb_image']:set_value('current_thumb_image');
                             	?>
                             <input type="hidden" name="current_thumb_image" value="<?php echo $imageName;?>">
                            <div class="form-label"><label class="control-label"></label></div>
                            <div class="controls">	
                            	<img src="<?php echo BOOK_URL.$this->uri->segment(3).'/'.$imageName;?>" />
                            </div>
                            <? }?>
                        </div>
						
                        <div class="control-group">
						<div class="form-label"><label class="control-label" for="status"><?php echo $this->lang->line('mobi_app_store_status');?></label><span class="asterisk">&nbsp;*</span></div>
						<div class="controls">
							<select id="status" name="status" data-rel="chosen">
							<option value="Published" <?php if (isset($query['status']) && $query['status'] == 'Published'):?>selected="selected"<?php endif; ?> <?php echo set_select('status','Published'); ?>>Yes</option>
							<option value="Pending" <?php if (isset($query['status']) && $query['status'] == 'Pending'):?>selected="selected"<?php endif; ?> <?php echo set_select('status','Pending'); ?>>No</option>							
							</select>
						</div>
						</div>                       
                        
					<div class="form-actions">
						<input name="submit" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('save');?>">
						<a href="<?php echo base_url(); ?>mobiappstore/" class="btn">Cancel</a>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		
		</div>
	</div><!--/span-->

</div><!--/row-->
<script type="text/javascript">
/*function changeContentType()
{
	contentType = $('#content_type').val();

	if(contentType == 'ebook') {
		$('#ebook_lbl').show();
		$('#audio_lbl').hide();
	}
	if(contentType == 'audio') {
		$('#audio_lbl').show();
		$('#ebook_lbl').hide();
	}
	if(contentType == 'combo') {
		$('#ebook_lbl').show();
		$('#audio_lbl').show();
	}
}*/
function author()
{
	PlanTypeId = $('#plan_type_id').val();

	if(PlanTypeId != '1') {
		$('#author-tab').show();
	} else{
		$('#author-tab').hide();
	}
}
$(document).ready(function() {
	/*changeContentType();*/
	author();
});
/*$('#content_type').change(function() {
	changeContentType();
});*/
$('#plan_type_id').change(function() {
	author();
});
</script>