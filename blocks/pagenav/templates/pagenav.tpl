


{strip}
					<div class="pagenav">
						<span class="pages">Страница {$current_page} из {$total_pages}</span>
						{foreach item=page from=$page_links}
							{if $page->type!='prev' && $page->type!='next'}
								{if $page->disabled}
									<span class="current">
										{$page->caption}
									</span>
								{else}									
									<a class="page smaller" href="{$page->link}">
										{$page->caption}
									</a>
								{/if}	
							{/if}
						{/foreach}
					</div>
{/strip}

