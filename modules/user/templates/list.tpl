



			<nav class="navbar navbar-default">
			
				<h4 class="navbar-text">{$module->gettext('User list')}</h4>
		
				<div class="col-sm-2 pull-right">
					<a class="btn btn-primary navbar-btn pull-right" data-action="item-edit" href="{$add_link}">{$module->gettext('Add user')}</a>
				</div>		
		    	
		    </nav>
		
			
			<p class="object_count">{$count_str}</p>
	
	
	
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="narrow">ID</th>
						<th class="narrow">{$entity->gettext('Roles')}</th>
						<th>{$entity->gettext('Name')}</th>
						<th>{$entity->gettext('Email')}</th>
						{*<th class="narrow">{$entity->gettext('Is active')}</th>*}						
						<th class="narrow">{$entity->gettext('Actions')}</th>
					</tr>
				</thead>
				<tbody>	
				
				    {foreach key=key item=object from=$objects name=objectlist}
					        <td class="narrow">
					        	{$object->id}
					        </td>
					        <td class="narrow">
					        	{foreach from=$object->roles item=role name=roles_loop}
					        		{$role}
					        		{if !$smarty.foreach.roles_loop.last}<br>{/if}
					        	{/foreach}
					        </td>
					        <td>
					        	{$object->user_name}
					        </td>
					        <td>
					        	<a href="mailto:{$object->email}">{$object->email}</a>
					        </td>
					        {*<td class="narrow">
					        	{if $object->is_active == 1}{$module->gettext('yes')}{else}{$module->gettext('no')}{/if}
					        </td>*}
					        <td class="narrow">
								<a href="{$object->edit_link}" class="btn btn-primary btn-xs" title={$module->gettext('Edit')}>
									<span class="glyphicon glyphicon-edit"></span>
								</a>
								<a onclick="return confirm('{$module->gettext('Really delete?')}');" href="{$object->delete_link}" class="btn btn-default btn-xs" title="{$module->gettext('Delete')}">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
					        </td>
					    </tr>
			    	{/foreach}
			
				</tbody>
			
			</table>	

    
    		{if $pagenav}
    			{$pagenav->render()}
    		{/if}
