

	<div class="item">
		<div class="number">
			<span>{$row_number}</span>
		</div>
		<div class="fields">
			{$row->render()}
		</div>	
		<div class="actions">
			{if seq_field_available}
				<a class="btn btn-s btn-default hidden move-upper" href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>
				<a class="btn btn-s btn-default hidden move-lower" href="#"><span class="glyphicon glyphicon-arrow-down"></span></a>
			{/if}
			<a href="{$object->delete_link}" class="btn btn-default btn-s remove" title="">
				<span class="glyphicon glyphicon-trash"></span>
			</a>			
		</div>
	</div>