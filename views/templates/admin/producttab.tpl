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
 <div class="panel col-lg-12">
 {if isset($id_product) AND $id_product}
 <script type="text/javascript">
 $(document).ready(function(){
    
    $('.design_tabs li').click(function(){
        var selected_tab = $(this).children().attr('href');
        
        $('.design_tabs').each(function(){
            $(this).find('.selected').removeClass('selected');
        });

        $(this).addClass('selected');

        $('.tabcontents').each(function(){
            $(this).children().hide();
        });
        $('.tabcontents').find(selected_tab).show();
    });

 })

 </script>
{include file="./tabs/script.tpl"}
{if $ps_version >= 1.7}<input type="hidden" name="key_tab" value="ModuleCustomproductdesign">{/if}
	<h3><i class="icon-star"></i> {l s='Product Design' mod='customproductdesign'}</h3>
	<div id="main" class="left">
        <ul class="design_tabs" data-persist="true">
            <li class="selected"><a href="#design_tab_1">{l s='Side 1' mod='customproductdesign'}</a></li>
            <li><a href="#design_tab_2">{l s='Side 2' mod='customproductdesign'}</a></li>
            <li><a href="#design_tab_3">{l s='Pricing' mod='customproductdesign'}</a></li>
            <li><a href="#design_tab_4">{l s='General' mod='customproductdesign'}</a></li>
        </ul>
        <div class="hide">
            <input id="id_product" name="id_product" type="hidden" value="{$id_product|escape:'htmlall':'UTF-8'}">
            <input id="id_customized" name="id_customized" type="hidden" value="{if isset($customization) AND $customization}{$customization.id_customized|escape:'htmlall':'UTF-8'}{/if}">
        </div>
        <div class="tabcontents">
            <div id="design_tab_1">
                {include file="./tabs/front.tpl"}
            </div><div class="clearfix"></div>

            <div id="design_tab_2" style="display:none;">
                {include file="./tabs/back.tpl"}
            </div><div class="clearfix"></div>

            <div id="design_tab_3" style="display:none;">
                {include file="./tabs/pricing.tpl"}
            </div><div class="clearfix"></div>

            <div id="design_tab_4" style="display:none;">
                {include file="./tabs/general.tpl"}
            </div><div class="clearfix"></div>

        </div>
    </div>
{else}
    <div class="alert alert-warning">
        {l s='There is 1 warning.' mod='customproductdesign'}
        <ul id="seeMore" style="display:block;">
            <li>{l s='You must save this product first.' mod='customproductdesign'}</li>
        </ul>
    </div>
{/if}

    <div class="clearfix"></div>
    <div class="panel-footer">
        <a href="{$the_link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default">
            <i class="process-icon-cancel"></i> {l s='Cancel' mod='customproductdesign'}
        </a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right">
            <i class="process-icon-save"></i> {l s='Save' mod='customproductdesign'}
        </button>&nbsp;
        <button type="submit" name="submitAddproductAndStay" style="margin-right:10px" class="btn btn-default pull-right">
            <i class="process-icon-save"></i> {l s='Save and stay' mod='customproductdesign'}
        </button>&nbsp;
    </div>
</div>
