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
<!-- customization panels -->

{if $error}
    <div class="alert alert-danger error">{$error|escape:'htmlall':'UTF-8'}</div>
{else}
    <div class="cpd_layers" id="cpd_groups_wrapper_{$cpd_group->id|escape:'htmlall':'UTF-8'}" data-key="{$cpd_group->id|escape:'htmlall':'UTF-8'}">
        <h3 class="layer_panel panel-heading clearfix {if $ps_version < 1.7}design_16{/if}{if $cpd_group->active == 1} ui_layer_active{else} ui_layer_nonactive{/if}">
            <a id="design-title-{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="design_title" href="{$controller_link|escape:'htmlall':'UTF-8'}&changeTitle&id_design={$cpd_group->id|escape:'htmlall':'UTF-8'}" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" title="{l s='Click to edit title' mod='customproductdesign'}">
                {if isset($cpd_group->design_title[$id_lang]) AND $cpd_group->design_title[$id_lang]}{$cpd_group->design_title[$id_lang|escape:'htmlall':'UTF-8']|escape:'htmlall':'UTF-8'}{else}{l s='Design Title' mod='customproductdesign'}{/if}
            </a>
            <div class="pull-right panel_actions">
                <a class="btn {if $cpd_group->active == 1}btn-success{else}btn-default{/if} status_layer" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" tite="{l s='Status' mod='customproductdesign'}">
                    <i class="icon-check material-icons action-enabled">{if $ps_version >= 1.7}check{/if}</i>
                </a>
                <a class="btn btn-danger remove_layer" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" tite="{l s='Delete' mod='customproductdesign'}">
                    <i class="icon-trash material-icons">{if $ps_version >= 1.7}delete{/if}</i>
                </a>
            </div>
        </h3>

        <div class="row cpd_layer{if $cpd_group->active == 1} ui_layer_active{else} ui_layer_nonactive{/if}">
            <div class="form-group col-lg-8">
                <label class="col-lg-12 control-label">{l s='Select image' mod='customproductdesign'}</label>
                <div class="col-lg-12">
                    <div id="cpd_image_thumbs">
                        <table class="table well">
                        {foreach from=$images item=image}
                            <td id="image-{$image.id_image|escape:'htmlall':'UTF-8'}">
                                <center>
                                    <img class="cpd_select_image img-thumbnail" src="{$image.src|escape:'htmlall':'UTF-8'}" width="98" height="98" data-dir="{$image.src|escape:'htmlall':'UTF-8'}" data-image-id="{$image.id_image|escape:'htmlall':'UTF-8'}" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}">
                                    <br/><div class="selected_image list-action-enable action-enabled"{if $cpd_group->path == $image.src} style="display:block;"{else} style="display:none;"{/if}><i class="material-icons">done</i></div>
                                </center>
                            </td>
                        {/foreach}
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-4">
                <label class="col-lg-12 control-label">{l s='Upload image' mod='customproductdesign'}</label>
                <div class="col-lg-12">
                    <center class="cpd_upload_area">
                        <img class="imgm img-thumbnail image_up" alt="{l s='Upload Image' mod='customproductdesign'}" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty.png" title="{l s='Upload Image' mod='customproductdesign'}">
                        <input id="cpd_image_upload_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="cpd_image_upload" type="file" name="cpd_fields[group_image_{$cpd_group->id|escape:'htmlall':'UTF-8'}]" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}">
                    </center>
                </div>
            </div>
<hr>
<div class="clearfix"></div>
            <div class="card fmm_bo_design_area_head col-lg-12">
                <div class="col-lg-12 card-header clearfix">
                    <h3><i class="material-icons">wallpaper</i> {l s='Design Area' mod='customproductdesign'}</h3>
                </div>
            </div>
            <div class="form-group col-lg-12 fmm_design_area_nav_super">
                <div class="row">
                    <div class="col-lg-3">
                        <a id="cpd_image_layer_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="btn cpd_image_layer_tag pull-left" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" data-tag="cpd_image_tag_sample" data-type="image"><i class="material-icons">add_a_photo</i> {l s='Add an image tag' mod='customproductdesign'}</a>
                    </div>
                    <div class="col-lg-3">
                        <a id="cpd_tag_layer_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="btn cpd_tag_layer_tag" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" data-tag="cpd_text_tag_sample" data-type="text"><i class="material-icons">format_size</i> {l s='Add a text tag' mod='customproductdesign'}</a>
                    </div>
                    <div class="col-lg-3">
                        <a id="cpd_window_layer_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="btn cpd_window_layer_tag" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}" data-tag="cpd_window_sample" data-type="window"><i class="material-icons">aspect_ratio</i> {l s='Add Workplace Area' mod='customproductdesign'}</a>
                    </div>
                    <div class="col-lg-3">
                        <a id="cpd_save_template" href="{$front_product_link|escape:'htmlall':'UTF-8'}?cpd_mode=designer&id_design={$cpd_group->id|escape:'htmlall':'UTF-8'}&id_employee={$_id_employee|escape:'htmlall':'UTF-8'}" target="_blank" class="btn cpd_save_template pull-right" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"><i class="material-icons">save_alt</i> {l s='Save as template' mod='customproductdesign'}</a>
                    </div>
                </div>
            </div>
            
            <!-- design preview -->
            <div id="cpd_design_preview_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="designer_panel">
                <div id="cpd_layer_top_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="cpd_layer_top" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"
                    style="display: inline-block;
                    left: 0%;
                    top: 0%;
                    width: 100%;
                    height: 100%;
                    position: relative;">
                <div class="image_loader" style="display: none"></div>
                {if isset($cpd_tags) && $cpd_tags}{include file ='./designElements.tpl'}{/if}
                    <img id="cpd_layer_image_{$cpd_group->id|escape:'htmlall':'UTF-8'}" class="inner_layer cpd_layer_image" src="{if isset($cpd_group->id) && isset($cpd_group->path) && $cpd_group->path}{$cpd_group->path|escape:'htmlall':'UTF-8'}{else}{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/front.png{/if}" width="540" data-id="{$cpd_group->id|escape:'htmlall':'UTF-8'}"
                    style="width: {$cpd_group->width|escape:'htmlall':'UTF-8'}%;height: {$cpd_group->height|escape:'htmlall':'UTF-8'}%;">
                    {if isset($cpd_window.id_product_customized_workplace) && $cpd_window.id_product_customized_workplace > 0}
                    <div id="cpd_window_{$cpd_window.id_product_customized_workplace|escape:'htmlall':'UTF-8'}" data-id-window="{$cpd_window.id_product_customized_workplace|escape:'htmlall':'UTF-8'}" class="cpd_workplace_area ui-resizable ui-draggable ui-draggable-handle"
                    style="left: {$cpd_window.pos_left|escape:'htmlall':'UTF-8'}%;top: {$cpd_window.pos_top|escape:'htmlall':'UTF-8'}%;height: {$cpd_window.height|escape:'htmlall':'UTF-8'}%;width: {$cpd_window.width|escape:'htmlall':'UTF-8'}%;">
                        <a class="cpd_workplace_locking_ico" onclick="lockThisWindow({$cpd_window.id_product_customized_workplace|escape:'htmlall':'UTF-8'}, this);"><i class="material-icons">lock</i></a>
                    <a class="cpd_remove_workplace pull-right" title="Delete">
                    <i class="material-icons">delete</i></a>
                    </div>
                    {/if}
                    <!--<pre>{*$cpd_group|@print_r*}</pre>-->
                </div>
            </div>
        </div>
    </div>
{/if}
