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
<div id="cpd_layers_section">
	<h6 onclick="cpdTackleVisibility(this);">{l s='Layers' mod='customproductdesign'}<span class="cpd_layers_pointer"></span></h6>
	<div id="cpd_layers_section_content">
	{if !empty($customization)}
	{foreach from=$customization item=tab name=tab}
		{if !empty($tab.tags) && $_id_design == $tab.designs->id}
			<ul class="cpd_layers_section_sortable" id="cpd_layers_section_sortable_{$tab.designs->id|escape:'htmlall':'UTF-8'}" style="display: none">
				{foreach from=$tab.tags item=tag name=tag}
				<li class="cpd_ui_layer cpd_ui_layer_{$tag.type|escape:'htmlall':'UTF-8'}"
				id="cpd_ui_layer_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-id-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
				data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-type="{$tag.type|escape:'htmlall':'UTF-8'}">{assign var='tag_price' value=($tag.price * $exchangeRate)}
		<input class="tag_price" id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag.price) AND $tag.price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[{$tag.id_design_tag|escape:'htmlall':'UTF-8'}]"><i>&nbsp;</i><span>{$tag.tag_title|escape:'htmlall':'UTF-8'}</span><img alt="X" class="cpd_layer_del" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/trash.png"><img class="cpd_layer_move_ico" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/layer_move.png"><strong>{Tools::displayPrice($tag_price)|escape:'htmlall':'UTF-8'}</strong>{if $tag.type == 'text'}
			<textarea
			id="left-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			placeholder="{l s='write text here....' mod='customproductdesign'}"
			class="cpd_tag form-control"
			name="tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			data-id="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
			data-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
			data-type="{$tag.type|escape:'htmlall':'UTF-8'}"
			{if isset($tag.length) && $tag.length}maxlength={$tag.length|escape:'htmlall':'UTF-8'}{/if}></textarea>{/if}</li>
				{/foreach}
			</ul>
		{/if}
	{/foreach}
	{/if}
	</div>
</div>