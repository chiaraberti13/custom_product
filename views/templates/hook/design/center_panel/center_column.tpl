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
<div id="cpd-editor-container" style="background: transparent none repeat scroll 0% 0%;">
	<!-- Design Preview -->
	<div id="design_preview" style="min-height:20em;">
		{if isset($customization) AND count($customization) > 0}
            {foreach from=$customization item=design name=design}
			    {include file='./preview.tpl' cpd_group=$design.designs cpd_tags=$design.tags cpd_window=$design.workplace index=$smarty.foreach.design.iteration}
            {/foreach}
		{/if}
	</div>
    <!-- /Design Preview -->
	<div class="clearfix"></div>
    <div class="design_loader" style="display:none;"></div>
</div>
<div id="product-part-container" class="">
	<div id="product-part-container">
		<ul id="cpd-parts-bar">
            {if isset($customization) AND count($customization) > 0}
                {foreach from=$customization item=design name=design}
                    {include file='./thumbs.tpl' design=$design.designs index=$smarty.foreach.design.iteration}
                {/foreach}
            {/if}
		</ul>
	</div>
</div>