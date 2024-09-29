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
<div id="cpd_img_txt_blox" class="DesignPanel">
	{if $CPD_ENABLE_DYNAMIC_PRICING > 0}
		<div class="cpd_extra_layer_btns">
			<a onclick="cpdAddDynamicLayer(0);" class="add_layer_txt"><i class="material-icons">add</i>
			<span class="cpd_i">{l s='Add Text Layer' mod='customproductdesign'}</span></a>
			<a onclick="cpdAddDynamicLayer(1);" class="add_layer_img"><i class="material-icons">add</i>
			<span class="cpd_i">{l s='Add Image Layer' mod='customproductdesign'}</span></a>
		</div>
	{/if}
	<div id="cpd_img_txt_blox_content">
	{if !empty($customization)}
	{foreach from=$customization item=tab name=tab}
		{if !empty($tab.tags)}
			<ul class="cpd_layers_section" id="cpd_layers_section_{$tab.designs->id|escape:'htmlall':'UTF-8'}">
				{foreach from=$tab.tags item=tag name=tag}
				<li
				   style="display: none"
				   class="cpd_layer cpd_layer_{$tag.type|escape:'htmlall':'UTF-8'}"
				id="cpd_layer_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-id-design="{$tag.id_design|escape:'htmlall':'UTF-8'}"
				data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
				data-type="{$tag.type|escape:'htmlall':'UTF-8'}">{assign var='tag_price' value=($tag.price * $exchangeRate)}
		<input class="tag_price" id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" type="hidden" value="{if isset($tag.price) AND $tag.price}{$tag_price|escape:'htmlall':'UTF-8'}{/if}" name="tag_price[{$tag.id_design_tag|escape:'htmlall':'UTF-8'}]">{if $tag.type == 'text'}
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
	<div id="cpd_toolset"></div>
</div>