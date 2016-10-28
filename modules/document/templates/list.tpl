


			<nav class="navbar navbar-default">
			
				<h4 class="navbar-text">{$module->gettext('Page list')}</h4>
		
				<div class="col-sm-2 pull-right">
					<a class="btn btn-primary navbar-btn pull-right" data-action="item-edit" href="{$add_link}">{$module->gettext('Add page')}</a>
				</div>		
		    	
		    </nav>
		
			
			<p class="object_count">{$count_str}</p>
	
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{$entity->gettext('Title')}</th>
						<th class="narrow">URL</th>
						<th class="narrow">{$entity->gettext('Ordering')}</th>
						<th class="narrow">{$entity->gettext('Is active')}</th>		
						<th class="narrow">{$entity->gettext('Menu')}</th>
						<th class="narrow">{$entity->gettext('Actions')}</th>
					</tr>
				</thead>
				<tbody>	
					{assign var="tr" value="0"}
					{include file=$line_template_path objects=$objects}
				</tbody>
			
			</table>	

    
    		{if $pagenav}
    			{$pagenav->render()}
    		{/if}


