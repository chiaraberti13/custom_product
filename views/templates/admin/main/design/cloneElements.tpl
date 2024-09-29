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

<!-- text -->
<div id="cpd_text_tag_sample" class="inner_layer cpd_text_layer layer_tag" style="display: none;">
    <div class="action_btn">
        <a class="cpd_edit_tag pull-left" title="{l s='Edit' mod='customproductdesign'}" onclick="cpdTriggerClickEdit(this);">
                <i class="material-icons">edit</i>
            </a>
        <a  class="tag_price" href="#" title="{l s='Click to modify' mod='customproductdesign'}"> 0.00</a>
        <a class="cpd_remove_img pull-right" title="{l s='Delete' mod='customproductdesign'}">
            <i class="process-icon-delete"></i><i class="material-icons">delete</i>
        </a>
    </div>
    <div class="cpd_img_icon">
        <center>{l s='Text' mod='customproductdesign'}</center>
    </div>
</div>
<!-- image layer -->
<div id="cpd_image_tag_sample" class="inner_layer cpd_image_layer layer_tag" style="display: none;">
    <div class="action_btn">
        <a class="cpd_edit_tag pull-left" title="{l s='Edit' mod='customproductdesign'}" onclick="cpdTriggerClickEdit(this);">
                <i class="material-icons">edit</i>
            </a>
        <a  class="tag_price" href="#" title="{l s='Click to modify' mod='customproductdesign'}"> 0.00</a>
        <a class="cpd_remove_img pull-right" title="{l s='Delete' mod='customproductdesign'}">
            <i class="process-icon-delete"></i><i class="material-icons">delete</i>
        </a>
    </div>
    <div class="cpd_img_icon">
        <img width="48" height="48" src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/empty_img.svg" alt="{l s='image tag' mod='customproductdesign'}">
    </div>
</div>

