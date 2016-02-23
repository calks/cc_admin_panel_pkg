
	jQuery(document).ready(function(){
		
		
		function setFieldsVisibility() {
			var link_type = jQuery('input[name=link_type]:checked').val();	
			//alert(link_type);
			jQuery('.type-alias, .type-page_itself').parents('.form-group').addClass('hidden');
			jQuery('.type-' + link_type).parents('.form-group').removeClass('hidden');
		}
		
		setFieldsVisibility();
		jQuery('input[name=link_type]').click(function(){
			setFieldsVisibility();	
		});
	});