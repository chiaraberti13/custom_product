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
<!-- text tags -->
{assign var='tag_price' value=($tagprice * $exchangeRate)}
{if $type == 'txt'}
    <div id="cpd_tag_1000{$count|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_text_layer layer_tag tags"
        data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}"
        data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}"
        data-type="text"
        data-draggable="1"
        data-resizable="1"
        style="left: 10%;
        top: 10%;
        width: 10%;
        height: 10%;
        position: absolute;"
        >
        <div class="cpd_icon">
            <center id="tag_text_1000{$count|escape:'htmlall':'UTF-8'}" class="tag_text">
            
            </center>
        </div>
        <div class="rotation_wrapper" style="display: none;">
            <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/delete.png" class="delete_tag mini-icons" data-tag="tag_text_1000{$count|escape:'htmlall':'UTF-8'}">
            <img id="setting-tag-1000{$count|escape:'htmlall':'UTF-8'}" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/tools.png" class="setting_tag mini-icons" data-tag="1000{$count|escape:'htmlall':'UTF-8'}" data-type="text" data-design="1000{$count|escape:'htmlall':'UTF-8'}" title="{l s='Tools' mod='customproductdesign'}">
        </div>
    </div>
<!-- image tags -->
{elseif $type == 'img'}
    <div id="cpd_tag_1000{$count|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_image_layer layer_tag tags"
        data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}"
        data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}"
        data-type="image"
        data-draggable="1"
        data-resizable="1"
        style="left: 10%;
        top: 10%;
        width: 30%;
        height: 30%;
        position: absolute;"
        >
        <div class="cpd_icon">
            <img
            id="tag_image_1000{$count|escape:'htmlall':'UTF-8'}"
            class="tag_image"
            width="100%"
            height="100%"
            src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty_img.svg"
            alt="{l s='image tag' mod='customproductdesign'}"
            style="visibility: hidden;"
            >
        </div>
        <div class="rotation_wrapper" style="display: none;">
            <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/delete.png" class="delete_tag mini-icons" data-tag="tag_image_1000{$count|escape:'htmlall':'UTF-8'}">
            <img id="setting-tag-1000{$count|escape:'htmlall':'UTF-8'}" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/tools.png" class="setting_tag mini-icons" data-tag="1000{$count|escape:'htmlall':'UTF-8'}" data-type="image" data-design="1000{$count|escape:'htmlall':'UTF-8'}" title="{l s='Tools' mod='customproductdesign'}">
        </div>
    </div>
{/if}
