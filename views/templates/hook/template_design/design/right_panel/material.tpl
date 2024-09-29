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
<!-- material -->
<div class="material-wrapper" id="material-wrapper">
    <input type="hidden" id="cpd_material_active" value="" />
    <label>{l s='Material' mod='customproductdesign'}</label>
    <select id="materialDropdown">
        {foreach from=$materials item=material}
            {assign var='price' value=($material.price * $exchangeRate)}
            <option value="{$material.id_material|escape:'htmlall':'UTF-8'}" data-imagesrc="{$material.material_path|escape:'htmlall':'UTF-8'}"
                data-description="{Tools::displayPrice($price)|escape:'htmlall':'UTF-8'}" data-price="{$price|escape:'htmlall':'UTF-8'}">{$material.material_name|escape:'htmlall':'UTF-8'}</option>
        {/foreach}
        <option value="0" data-imagesrc="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/cross.png" data-description="{Tools::displayPrice(0.00)|escape:'htmlall':'UTF-8'}" data-price="{Tools::displayPrice(0.00)|escape:'htmlall':'UTF-8'}" selected="selected">{l s='None' mod='customproductdesign'}</option>
    </select>
</div>
