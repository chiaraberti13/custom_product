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

<div id="cpd_from_wrapper" class="product-tab panel">
    <div class="alert alert-info" role="alert">
        <div class="alert-text">
            {l s='Please note that the designs you add are draggable for positioning and also can be renamed by clicking on title.' mod='customproductdesign'}
        </div>
    </div>
    <input type="hidden" name="id_product" value="{$id_product|escape:'htmlall':'UTF-8'}">
    <div class="panel-heading form-control clearfix card">
        <div class="col-lg-12 card-header clearfix">
            <h3><i class="material-icons">wallpaper</i> {l s='Custom Product Designs' mod='customproductdesign'}</h3>
            <a id="cpd_add" class="btn btn-primary right_floater {if $ps_version < 1.7}add_16{/if}">
            {if $ps_version < 1.7}<i class="icon-plus-sign"></i>{else}<i class="material-icons">add_to_photos</i>{/if}
                {l s='Add a Design' mod='customproductdesign'}
            </a>
            <a id="cpd_goto_premades" class="btn btn-primary right_floater {if $ps_version < 1.7}add_16{/if}">
            {if $ps_version < 1.7}<i class="icon-image"></i>{else}<i class="material-icons">style</i>{/if}
                {l s='Pre-Made Designs' mod='customproductdesign'}
            </a>
        </div>
    </div>
    <div class="general_settings cpd_layers">
        <h3 class="layer_panel panel-heading clearfix {if $ps_version < 1.7}design_16{/if}">{l s='General Settings' mod='customproductdesign'}</h3>
        {include file ='./general/general_settings.tpl'}
    </div>
    <div class="clearfix m-b-1"></div>
    <!--  clone designs -->
    <div id="design-panel" style="display: {if isset($designs) && $designs}block{else}none{/if};">
        {if isset($designs) && $designs}
            {foreach from=$designs item=design}
                {include file ='./design/formGroups.tpl' cpd_group=$design.designs cpd_tags=$design.tags cpd_window=$design.workplace}
            {/foreach}
        {/if}
        {include file ='./design/cloneElements.tpl'}
    </div>
    <div id="premade_designs_product">
        {include file ='./design/premade_designs.tpl'}
    </div>
</div>
