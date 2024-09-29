{*
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by Satoshi Brasileiro.
*
*  @author    Satoshi Brasileiro
*  @copyright Satoshi Brasileiro 2021
*  @license   Single domain
*}
<!-- text tags -->
{foreach from=$cpd_tags item=tag}
{if $tag.type == 'text'}
    <div id="cpd_tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_text_layer layer_tag tags"
        data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        style="left: {$tag.pos_left|escape:'htmlall':'UTF-8'}%;
        top: {$tag.pos_top|escape:'htmlall':'UTF-8'}%;
        width: {$tag.width|escape:'htmlall':'UTF-8'}%;
        height: {$tag.height|escape:'htmlall':'UTF-8'}%;
        position: absolute;"
        >
        <div class="action_btn">
            <a class="cpd_edit_tag pull-left" title="{l s='Edit' mod='customproductdesign'}" onclick="cpdTriggerClickEdit(this);">
                <i class="material-icons">edit</i>
            </a>
            <a id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" class="tag_price" href="{$controller_link|escape:'htmlall':'UTF-8'}&updateTag&id_tag={$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-design="{$tag.id_design|escape:'htmlall':'UTF-8'}" title="{l s='Click to modify' mod='customproductdesign'}"> {if $tag.price > 0}{Tools::displayPrice($tag.price)|escape:'htmlall':'UTF-8'}{else}0.00{/if}</a>
            <a class="cpd_remove_img pull-right" title="{l s='Delete' mod='customproductdesign'}">
                <i class="material-icons">delete</i>
            </a>
        </div>
        <div class="cpd_img_icon">
            <center>{l s='Text' mod='customproductdesign'}</center>
        </div>
    </div>
<!-- image tags -->
{elseif $tag.type == 'image'}
    <div id="cpd_tag_{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        class="inner_layer cpd_image_layer layer_tag tags"
        data-id-tag="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}"
        style="left: {$tag.pos_left|escape:'htmlall':'UTF-8'}%;
        top: {$tag.pos_top|escape:'htmlall':'UTF-8'}%;
        width: {$tag.width|escape:'htmlall':'UTF-8'}%;
        height: {$tag.height|escape:'htmlall':'UTF-8'}%;
        position: absolute;"
        >
        <div class="action_btn">
            <a class="cpd_edit_tag pull-left" title="{l s='Edit' mod='customproductdesign'}" onclick="cpdTriggerClickEdit(this);">
                <i class="material-icons">edit</i>
            </a>
            <a id="design-tag-{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" class="tag_price" href="{$controller_link|escape:'htmlall':'UTF-8'}&updateTag&id_tag={$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id="{$tag.id_design_tag|escape:'htmlall':'UTF-8'}" data-id-design="{$tag.id_design|escape:'htmlall':'UTF-8'}" title="{l s='Click to modify' mod='customproductdesign'}"> {if $tag.price > 0}{Tools::displayPrice($tag.price)|escape:'htmlall':'UTF-8'}{else}0.00{/if}</a>
            <a class="cpd_remove_img pull-right" title="{l s='Delete' mod='customproductdesign'}">
                <i class="process-icon-delete"></i><i class="material-icons">delete</i>
            </a>
        </div>
        <div class="cpd_img_icon">
            <img width="48" height="48" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty_img.svg" alt="{l s='image tag' mod='customproductdesign'}">
        </div>
    </div>
{/if}
{/foreach}
