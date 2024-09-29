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
{assign var='tag_price' value=($tagprice * $exchangeRate)}
<li class="cpd_ui_layer cpd_ui_layer_{$type|escape:'htmlall':'UTF-8'}"
id="cpd_ui_layer_1000{$count|escape:'htmlall':'UTF-8'}"
data-id-design="{$_id_design|escape:'htmlall':'UTF-8'}"
data-id-tag="1000{$count|escape:'htmlall':'UTF-8'}"
data-type="{$type|escape:'htmlall':'UTF-8'}">{if $type == 'text'}<i class="material-icons">text_fields</i>{else}<i class="material-icons">wallpaper</i>{/if}<span>{l s='Tag' mod='customproductdesign'}&nbsp;10{$count|escape:'htmlall':'UTF-8'}</span><i class="material-icons cpd_layer_del">delete</i><i class="material-icons cpd_layer_move_ico">import_export</i><strong>{Tools::displayPrice($tag_price)|escape:'htmlall':'UTF-8'}</strong></li>