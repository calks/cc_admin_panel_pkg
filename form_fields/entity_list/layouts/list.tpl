


	<div class="entity-list-field" id="{$field_id}">
		<a href="#" class="btn btn-primary btn-s add-item">
			<span class="glyphicon glyphicon-plus"></span>
			{$field->gettext('Add')}
		</a>
	
		<div class="item-list">
			{foreach item=item from=$items}
				{$item}
			{/foreach}
		</div>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			return new EntityListField(
				'#{$field_id}', 
				'{$field_type}', 
				'{$field_name}',
				'{$entity_name}'
			);
		});
	</script>
	