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

<!-- Left Tabs -->
{if isset($customization) AND count($customization) > 0}
	{if $CPD_ENABLE_DYNAMIC_PRICING > 0}
		<!-- custom design dynamic layer addition -->
		{*include file='./cpd_dynamic_layers.tpl'*}
	{/if}
	{if $CPD_ENABLE_PRE_DESIGNS > 0}
		<!-- custom design pre made templates -->
		{include file='./cpd_templates.tpl'}
	{/if}
	<!-- designs Tabs -->
	{include file='./designs_tab.tpl'}
	<!-- custom design tabs -->
	{include file='./cpd_tab.tpl'}
{/if}