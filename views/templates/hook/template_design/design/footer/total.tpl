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
<div class="cpd-qty-container">
	<div id="total-price" class="our_price_display">
		<div id="cpd_grand_total">
			<span class="cpd_total_lbl">{l s='Total' mod='customproductdesign'}</span>
			<span class="total_custom_price">{Tools::displayPrice($product_price)|escape:'htmlall':'UTF-8'}</span>
			<div class="cpd_more_prices"><i class="material-icons">error_outline</i>
			<div id="cpd_sub_totals">
			<table width="100%">
				<tr class="total_order">
					<td align="left">{l s='Price' mod='customproductdesign'}</td>
					<td><span class="cproduct_price" data-price="{Tools::displayPrice($product_price)|escape:'htmlall':'UTF-8'}">{Tools::displayPrice($product_price)|escape:'htmlall':'UTF-8'}</span></td>
				</tr>
	
				<tr class="total_order">
					<td align="left">{l s='Customization' mod='customproductdesign'}</td>
					<td><span class="custom_price">{Tools::displayPrice(0.0)|escape:'htmlall':'UTF-8'}</span></td>
				</tr>
			</table>
		</div>
			</div>
		</div>
		
		<div style="display: none;" class="price_update"></div>
	</div>
</div>