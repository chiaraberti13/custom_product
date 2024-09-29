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

<div class="DesignPanel DesignPanelClosed" id="designs-panel">
	<div class="DesignPanelContent" id="designs-container">
        {assign var=params value=['action' => 'add_to_cart_explict']}
        <form id="customized-data-form" class="defaultForm" method="post" action="{$link->getModuleLink('customproductdesign', 'cpdesign', [], true)|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_to_cart_explict">
            <input type="hidden" name="has_attributes" value="{$has_attributes|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="cpd_product" value="{$id_product_old|escape:'htmlall':'UTF-8'}">
		    <table class="well" width="100%"><tbody id="selected-combinations"></tbody></table>
        </form>
		<p class="cpd_abys">{l s='You do not have any designs yet.' mod='customproductdesign'}</p>
    </div>
</div>