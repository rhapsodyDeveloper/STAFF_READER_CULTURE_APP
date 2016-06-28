<?php echo form_open(current_url()); ?><?php echo form_close(); ?>	
<div class="box radius5 border">
	<div class="header gradient2">
		<p><?php echo $this->lang->line('change_order');?></p>
	</div>
	<div class="content">
		<script src="<?php echo $this->config->item('admin_assets_path');?>js/jquery.ui.nestedSortable.js"></script>
		<script>
		$(document).ready(function() {
			$('#item_list').nestedSortable({
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				'opacity': .6,
				'items':'li',
				'nested':'ol',
				stop: function(i) {
					$.post("<?php echo base_url(); ?>reorder_<?php echo $this->uri->segment(2);?>/", { items: $("#item_list").nestedSortable('toArray'), menu_id: <?php echo $this->uri->segment(4); ?>, <?php echo $this->config->item('csrf_token_name'); ?>: $('input[name="ci_token"]').val() });				   
				}					
			});
		});
		</script>
		<p style="padding-bottom:10px;"><?php echo $this->lang->line('change_order_help');?></p>
		
		<ol id="item_list" class="reorder">
			<?php echo reorder_items();?>
		</ol>

	</div>
</div>

<p>
<a href="javascript:history.back(1)" class="submit"><?php echo $this->lang->line('back');?></a>
</p>