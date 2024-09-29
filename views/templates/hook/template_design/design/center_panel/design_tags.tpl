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
{foreach from=$cpd_tags item=tag}
{assign var='tag_price' value=($tag.price * $exchangeRate)}
{if $tag.type == 'text'}
{*assign var=tag_txt value=Designer::getTagText($tag.id_design_tag, $id_template)*}
    <div id="cpd_tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_text_layer layer_tag tags"
        data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}"
        data-type="text"
        data-draggable="{$tag.draggable|escape:'htmlall':'UTF-8'}"
        data-resizable="{$tag.resizable|escape:'htmlall':'UTF-8'}"
        style="{if empty($tag.style)}left: {$tag.pos_left|escape:'htmlall':'UTF-8'}%;
        top: {$tag.pos_top|escape:'htmlall':'UTF-8'}%;
        width: {$tag.width|escape:'htmlall':'UTF-8'}%;
        height: {$tag.height|escape:'htmlall':'UTF-8'}%;
        position: absolute;{else}{$tag.style|escape:'htmlall':'UTF-8'}{/if}"
        >
        <div class="cpd_icon">
            <center id="tag_text_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}" class="tag_text design"{if !empty($tag.child_style)} style="{$tag.child_style|escape:'htmlall':'UTF-8'}"{/if}>
            {if !empty($tag.value)}{$tag.value|escape:'htmlall':'UTF-8'}{/if}
            </center>
        </div>
        <div class="rotation_wrapper" style="display: none;">
            <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/delete.png" class="delete_tag mini-icons" data-tag="tag_text_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}">
            <img id="setting-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/tools.png" class="setting_tag mini-icons" data-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-type="text" data-design="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" title="{l s='Tools' mod='customproductdesign'}">
        </div>
    </div>
<!-- image tags -->
{elseif $tag.type == 'image'}
{*assign var=tag_txt value=Designer::getTagText($tag.id_design_tag, $id_template)*}
    <div id="cpd_tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_image_layer layer_tag tags"
        data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}"
        data-type="image"
        data-draggable="{$tag.draggable|escape:'htmlall':'UTF-8'}"
        data-resizable="{$tag.resizable|escape:'htmlall':'UTF-8'}"
        style="{if empty($tag.style)}left: {$tag.pos_left|escape:'htmlall':'UTF-8'}%;
        top: {$tag.pos_top|escape:'htmlall':'UTF-8'}%;
        width: {$tag.width|escape:'htmlall':'UTF-8'}%;
        height: {$tag.height|escape:'htmlall':'UTF-8'}%;
        position: absolute;{else}{$tag.style|escape:'htmlall':'UTF-8'}{/if}"
        >
        <div class="cpd_icon">
            {if !empty($tag.value)}
            <img
            id="tag_image_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
            class="tag_image design"
            data-price="{if $tag_price >= 0}{$tag_price|escape:'htmlall':'UTF-8'}{else}-1{/if}"
            src="{$tag.value|escape:'htmlall':'UTF-8'}"{if !empty($tag.child_style)} style="{$tag.child_style|escape:'htmlall':'UTF-8'}"{/if}
            >
            {/if}
        </div>
        <div class="rotation_wrapper" style="display: none;">
            <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/delete.png" class="delete_tag mini-icons" data-tag="tag_image_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}">
            <img id="setting-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/tools.png" class="setting_tag mini-icons" data-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-type="image" data-design="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" title="{l s='Tools' mod='customproductdesign'}">
        </div>
    </div>
{/if}
{/foreach}
