{*
* Custom Product Design
*
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by Satoshi Brasileiro.
*
*  @author    Satoshi Brasileiro
*  @copyright 2021 Satoshi Brasileiro All right reserved
*  @license   Single domain
*}
<div id="cpd_layers_section" class="DesignPanel">
	<div id="cpd_layers_section_content">
	{if !empty($customization)}
	{foreach from=$customization item=tab name=tab}
		{if !empty($tab.tags)}
			<ul class="cpd_layers_section_sortable" id="cpd_layers_section_sortable_{$tab.designs->id|escape:'htmlall':'UTF-8'}">
				{foreach from=$tab.tags item=tag name=tag}
				<li class="cpd_ui_layer cpd_ui_layer_{$tag.type|escape:'htmlall':'UTF-8'}"
				id="cpd_ui_layer_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-id-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
				data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-type="{$tag.type|escape:'htmlall':'UTF-8'}">{assign var='tag_price' value=($tag.price * $exchangeRate)}
		<input class="tag_price" id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag.price) AND $tag.price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[{$tag.id_design_tag|escape:'htmlall':'UTF-8'}]">{if $tag.type == 'text'}<i class="material-icons">text_fields</i>{else}<i class="material-icons">wallpaper</i>{/if}<span>{$tag.tag_title|escape:'htmlall':'UTF-8'}</span><i class="material-icons cpd_layer_del">delete</i><i class="material-icons cpd_layer_move_ico">import_export</i><strong>{Tools::displayPrice($tag_price)|escape:'htmlall':'UTF-8'}</strong></li>
				{/foreach}
			</ul>
		{/if}
	{/foreach}
	{/if}
	</div>
</div>