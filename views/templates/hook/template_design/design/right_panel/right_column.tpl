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
<div id="cpd-design-btn-box">
	{if isset($combinations) AND $combinations|count > 0}
		<div id="cpd_product_opts_btn">
			<a class="cpd_a" onclick="cpdSwitch(this, 'cpd-variants');"><i class="material-icons">settings</i>
			<span class="cpd_i">{l s='Product Options' mod='customproductdesign'}</span></a>
			{include file='./combinations.tpl'}
		</div>
	{/if}
	{if isset($materials) AND $materials}
		<div id="cpd_product_materials_btn">
			<a class="cpd_a" onclick="cpdSwitch(this, 'material-wrapper');"><i class="material-icons">texture</i>
			<span class="cpd_i">{l s='Materials' mod='customproductdesign'}</span></a>
			{include file='./material.tpl'}
		</div>
	{/if}
</div>