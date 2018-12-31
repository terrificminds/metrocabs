jQuery(document).ready(function($){
	$(".layout_picker_content [name='style']").change(function(){
		$(".p_table_1").removeClass(function (index, css){
			return (css.match (/\p_table_1_\S+/g) || []).join(' ');
		}).addClass("p_table_1_" + $(this).val());
	});
});