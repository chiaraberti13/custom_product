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
<div id="cpd_footer_wrap">
	<div id="cpd_footer">
		<div id="cpd_footer_navigation">
			<ul>
				<li><button id="preview-design" class="cpd_preview_btn">
				<i class="material-icons">visibility</i>
				<span>{l s='Preview' mod='customproductdesign'}</span>
			</button></li>
				<li><button id="reset-name-num" class="cpd_reset_btn">
				<i class="material-icons">settings_backup_restore</i>
				<span>{l s='Reset' mod='customproductdesign'}</span>
			</button></li>
				<li class="last_ele"><button id="design-list" class="cpd_addmore_btn add_more_design">
				<i class="material-icons">library_add</i>
				<span>{l s='Add to My Designs' mod='customproductdesign'}</span>
			</button></li>
			</ul>
		</div>
		{include file='./total.tpl'}
		{include file='./add_to_cart.tpl'}
	</div>
</div>