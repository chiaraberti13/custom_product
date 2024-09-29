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

<div id="design-view-{$cpd_group->id|escape:'htmlall':'UTF-8'}" data-index="{$index|escape:'htmlall':'UTF-8'}"
    class="design_container canvas-container card"
    data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"
    style="{if $index == 1}display: block;{else}display:none;{/if}">
	<div class="working_loader" style="display:none"></div>
	<div class="front_area" id="front-dragable-wrapper-{$cpd_group->id|escape:'htmlall':'UTF-8'}">
		<div id="cpd_design_preview_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="designer_panel" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}">
            <div id="cpd_layer_top_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="cpd_layer_top" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"
                style="display: inline-block;
                left: {$cpd_group->left|escape:'htmlall':'UTF-8'}%;
                top: {$cpd_group->top|escape:'htmlall':'UTF-8'}%;
                width: {$cpd_group->width|escape:'htmlall':'UTF-8'}%;
                height: {$cpd_group->height|escape:'htmlall':'UTF-8'}%;
                position: relative;">
                
                <img id="cpd_layer_image_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="inner_layer cpd_layer_image" src="{if isset($cpd_group->id) && isset($cpd_group->path) && $cpd_group->path}{$cpd_group->path|escape:'htmlall':'UTF-8'}{else}{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/front.png{/if}" width="540" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"
                style="width: {$cpd_group->width|escape:'htmlall':'UTF-8'}%;height: {$cpd_group->height|escape:'htmlall':'UTF-8'}%;">
{if !empty($cpd_window) && $cpd_window.id_product_customized_workplace > 0}<div class="cpd_workplace_holder_blk" style="position: absolute;left: {$cpd_window.pos_left|escape:'htmlall':'UTF-8'}%;top: {$cpd_window.pos_top|escape:'htmlall':'UTF-8'}%;height: {$cpd_window.height|escape:'htmlall':'UTF-8'}%;width: {$cpd_window.width|escape:'htmlall':'UTF-8'}%;">{/if}
                {if isset($cpd_tags) AND $cpd_tags}
                    <div class="cpd_busy" style="display:none"></div>
                    <input id="cpd_material_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="design" type="hidden" data-price="0">
                    {include file ='./design_tags.tpl'}
                {/if}
{if !empty($cpd_window) && $cpd_window.id_product_customized_workplace > 0}</div>{/if}
            </div>
        </div>
	</div>
</div>