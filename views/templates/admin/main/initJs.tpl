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
<script type="text/javascript">
    var id_product = parseInt("{$id_product|escape:'htmlall':'UTF-8'}");
    var cpd_ajax_group = "{$the_link->getAdminLink('CustomProductDesigner')|escape:'htmlall':'UTF-8'}";
    cpd_ajax_group = cpd_ajax_group.replace('&amp;', '&');
    var mini_loader = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/mini_loader.gif";
    var allowed_sized = parseFloat("{$PS_ATTACHMENT_MAXIMUM_SIZE|escape:'htmlall':'UTF-8'}");
    // translations
    var labels = {
        side_title_label : "{l s='Side' mod='customproductdesign' js=1}",
        conf_text : "{l s='Are you sure you want to remove design group?' mod='customproductdesign' js=1}",
        image_type_error : "{l s='Invalid image type.' mod='customproductdesign' js=1}",
        image_size_error : "{l s='Size exceeds. Upload a smaller image.' mod='customproductdesign' js=1}",
        types : "{l s='Allowed types: PNG, JPG, JPEG' mod='customproductdesign' js=1}",
    };

    $(function() {
        Designer.runScript();
    })
</script>
