<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/layout_picker/layout_picker.css" />
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/layout_picker/layout_picker.js"></script>
<div class="layout_picker <?php echo $theme_options["layout_picker_param"];?>">
	<a href="#" class="layout_picker_icon">&nbsp;</a>
	<div class="layout_picker_content">
		<?php
		global $theme_options;
		if($theme_options["layout_picker_param"]=="timetable")
		{
		?>
		<h3 class="layout_picker_header"><?php _e("Examples", 'live_preview'); ?></h3>
		<ul class="layout_picker_layout_list">
			<li>
				<a href="<?php echo get_permalink(get_page_by_path('timetable-for-wordpress')); ?>" <?php echo (is_page("timetable-for-wordpress") ? ' class="selected"' : ''); ?>>
					<?php _e("Table 1", 'live_preview'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo get_permalink(get_page_by_path('timetable-for-wordpress-sample-2')); ?>" <?php echo (is_page("timetable-for-wordpress-sample-2") ? ' class="selected"' : ''); ?>>
					<?php _e("Table 2", 'live_preview'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo get_permalink(get_page_by_path('timetable-for-wordpress-sample-3')); ?>" <?php echo (is_page("timetable-for-wordpress-sample-3") ? ' class="selected"' : ''); ?>>
					<?php _e("Table 3", 'live_preview'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo get_permalink(get_page_by_path('timetable-for-wordpress-sample-4')); ?>" <?php echo (is_page("timetable-for-wordpress-sample-4") ? ' class="selected"' : ''); ?>>
					<?php _e("Table 4", 'live_preview'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo get_permalink(get_page_by_path('timetable-for-wordpress-sample-5')); ?>" <?php echo (is_page("timetable-for-wordpress-sample-5") ? ' class="selected"' : ''); ?>>
					<?php _e("Table 5", 'live_preview'); ?>
				</a>
			</li>
		</ul>
		<?php
		}
		else if($theme_options["layout_picker_param"]=="css3_grid")
		{
		?>
		<h3 class="layout_picker_header"><?php _e("Global Style", 'live_preview'); ?></h3>	
		<ul class="layout_picker_layout_list">
			<li>
				<select name="style">
				<option value="1" selected="selected">Style 1</option>
				<option value="2">Style 2</option>
				<option value="3">Style 3</option>
				<option value="4">Style 4</option>
				<option value="5">Style 5</option>
				<option value="6">Style 6</option>
				<option value="7">Style 7</option>
				<option value="8">Style 8</option>
				<option value="9">Style 9</option>
				<option value="10">Style 10</option>
				<option value="11">Style 11</option>
				<option value="12">Style 12</option>
				<option value="13">Style 13 (medicenter blue)</option>
				<option value="14">Style 14 (medicenter green)</option>
				<option value="15">Style 15 (medicenter orange)</option>
				<option value="16">Style 16 (medicenter red)</option>
				<option value="17">Style 17 (medicenter turquoise)</option>
				<option value="18">Style 18 (medicenter violet)</option>
				</select>
			</li>
		</ul>
		<?php
		}
		?>
	</div>
</div>
