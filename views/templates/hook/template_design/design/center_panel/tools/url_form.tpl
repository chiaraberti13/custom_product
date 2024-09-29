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
{if $LOGO_UPLOAD_URL > 0}
{assign var=params value=['action' => 'upload_url_image']}
<form id="logo-upload-url" class="form-logo-upload" method="post" action="{$cpd_link->getModuleLink('customproductdesign', 'cpdesign', $params)|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data">
    <div style="display: none;" class="image_processing"></div>
    <p id="hasError-0" class="alert alert-success conf" style="display:none;"></p>
    <p id="hasError-1" class="alert alert-danger error" style="display:none;"></p>

    <input type="hidden" name="id_product" value="{$cpd_id_product|escape:'htmlall':'UTF-8'}">
    <div class="link_container">
        <input type="text" name="image_url" id="image_url" class="from-control"  placeholder="{l s='Upload From URL' mod='customproductdesign'}">
        <button class="c_p_d-button btn button" type="submit" name="upload_url_image">{l s='Go' mod='customproductdesign'}</button>
    </div>
</form>
{/if}