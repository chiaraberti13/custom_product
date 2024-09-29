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
<div id="cpd-variants" class="cpd_product-variants">
	{foreach from=$combinations key=key item='combination'}
		<label>{$combination.name|escape:'htmlall':'UTF-8'}</label>
		{if $combination.group_type == 'color'}
			<ul>
				{foreach from=$combination.attributes key=id_attribute item='attributes'}
				<li class="cpd_attribute_list">
					<label class="cpd_attribute_selection {if isset($combination.default) && $combination.default == $id_attribute}selected_attribute{/if}" for="cpd_attribute_{$id_attribute|escape:'htmlall':'UTF-8'}" title="{$attributes.name|escape:'htmlall':'UTF-8'}">
						<input class="cpd_attribute_radio" id="cpd_attribute_{$id_attribute|escape:'htmlall':'UTF-8'}" type="radio" name="cpd_group[{$key|escape:'htmlall':'UTF-8'}]" value="{$id_attribute|escape:'htmlall':'UTF-8'}" {if isset($combination.default) && $combination.default == $id_attribute}checked="checked"{/if}>
						<div class="cpd_attribute_container">
						{if isset($attributes.texture) && $attributes.texture}
							<img src="{$attributes.texture|escape:'htmlall':'UTF-8'}" alt="{$attributes.name|escape:'htmlall':'UTF-8'}" title="{$attributes.name|escape:'htmlall':'UTF-8'}" width="60" height="60" />
						{else}
							<div style="background:{$attributes.html_color_code|escape:'htmlall':'UTF-8'};width: 100%;height: 100%;">
							</div>
						{/if}
						</div>
					</label>
				</li>
				{/foreach}
			</ul>
		{else}
			<select class="form-control cpd_attribute_select" name="cpd_group[{$key|escape:'htmlall':'UTF-8'}]">
				{foreach from=$combination.attributes key=id_attribute item='attributes'}
					<option title="{$attributes.name|escape:'htmlall':'UTF-8'}" value="{$id_attribute|escape:'htmlall':'UTF-8'}" {if isset($combination.default) && $combination.default == $id_attribute}selected="selected"{/if}>{$attributes.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		{/if}
	{/foreach}
</div>