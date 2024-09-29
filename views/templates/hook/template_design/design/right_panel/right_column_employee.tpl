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
	<div class="margin"></div>
	<div class="hidden_data">
		<input id="cpd_id_product" type="hidden" name="cpd_id_product" value="{$id_product_old|escape:'htmlall':'UTF-8'}">
	</div>
	<button id="cpd_save_template" class="btn btn-danger button cpd_save_template" onclick="cpdSaveTemplate({$_id_design|escape:'htmlall':'UTF-8'}, this)">
		{l s='Save Template' mod='customproductdesign'}
	</button>
	<div style="display: none; width: 100%; text-align: center" id="cpd_save_template_loader"><img src="{$_base_link|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/load.gif" /></div>
</div>