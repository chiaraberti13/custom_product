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
{if !empty($design_templates)}
<div class="DesignPanel DesignPanelClosed" id="templates-designs-panel" data-accordion>
	<div class="DesignPanelContent" id="templates-designs-container">
		<ul>
			{foreach from=$design_templates item=item}
				<li><a title="{l s='Use this Idea' mod='customproductdesign'}" onclick="cpdGetDesignElements({$item.id_cpd_saved_templates|escape:'htmlall':'UTF-8'},{$item.id_design|escape:'htmlall':'UTF-8'},this);"><img src="{$item.base_img|escape:'htmlall':'UTF-8'}" /><span>{l s='Use this Idea' mod='customproductdesign'}</span></a></li>
			{/foreach}
		</ul>
    </div>
</div>
{/if}