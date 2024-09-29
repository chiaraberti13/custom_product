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
<div class="add_to_cart">
	<div class="add_to_cart_container">
		<div class="qty_update"></div>
		<div class="hidden_data">
			<input id="cpd_id_product" type="hidden" name="cpd_id_product" value="{$id_product_old|escape:'htmlall':'UTF-8'}">
		</div>
		<input type="number" id="cpd_qty_wanted" name="cpd_qty_wanted" min="1" max="100000" value="1" onchange="cpdQtyModify(this.value, this);" />
		<a id="add_custom_product_to_cart" class="btn btn-success button c_p_d-button" href="javascript:void(0);" name="add_to_cart_explict" type="submit">
			{l s='Add to Cart' mod='customproductdesign'}
		</a>
		
	</div>
</div>