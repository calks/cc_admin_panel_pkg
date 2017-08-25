


					    {foreach key=key item=object from=$objects name=objectlist}
					    
					    	{assign var="tr" value=$tr+1}
							{if $object->parent_id==""}
								{assign var='level' value=0}
							{/if}
    						{if $level>10}
    							{assign var='level' value=10}
    						{/if}
					    
					    	
			    	        {if $smarty.foreach.objectlist.first}			    	        
	            				{assign var=no_up value="1"}
	            			{else}
	            				{assign var=no_up value="0"}
	            			{/if}
	            				
	        				{if $smarty.foreach.objectlist.last}	        						
	            				{assign var=no_down value="1"}
	            			{else}	            					
	            				{assign var=no_down value="0"}
	        				{/if}

						    <tr>
						        <td style="padding-left:{$level*20+5}px">
						        	{if $object->category==0}
						        		<span class="glyphicon glyphicon-folder-open"></span>
						        	{else}
						        		<span class="glyphicon glyphicon-file"></span>
						        	{/if}	
						        	{$object->title} 						        		
						        </td>
						        <td class="narrow">
						        	{if $object->open_link != ''}
						        		[{$object->front_link}]
						        	{else}
						        		{$object->front_link}
						        	{/if}
						        </td>
						        
						        <td class="narrow">						        
									<div class="btn-group" role="group">
							        	{if !$no_up}<a class="btn btn-xs btn-default" href="{$object->moveup_link}"><span class="glyphicon glyphicon-arrow-up"></span></a>{/if}
							        	{if !$no_down}<a class="btn btn-xs btn-default" href="{$object->movedown_link}"><span class="glyphicon glyphicon-arrow-down"></a>{/if}
	  								</div>
						        </td>
						        
						        <td class="narrow">
						        	{if $object->is_active}
						        		<span class="glyphicon glyphicon-ok"></span>
						        	{else}
						        								        	
						        	{/if}
						        </td>
						        <td class="narrow">						        
						        	{$object->menu_str}
						        </td>
						        <td class="narrow">
									<a href="{$object->edit_link}" class="btn btn-primary btn-xs" title="{$module->gettext('Edit')}">
										<span class="glyphicon glyphicon-edit"></span>
									</a>
									<a onclick="return confirm('{$module->gettext('Really delete?')}');" href="{$object->delete_link}" class="btn btn-default btn-xs" title="{$module->gettext('Delete')}">
										<span class="glyphicon glyphicon-trash"></span>
									</a>
						        </td>

						    </tr>
						    
						    {if $object->children}
						        {assign var='level' value=$level+1}
						        {include file=$line_template_path objects=$object->children}
						        {assign var='level' value=$level-1}
						    {/if}

				    	{/foreach}





