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

{if (isset($LOGO_UPLOAD_EN_DS) AND $LOGO_UPLOAD_EN_DS == 1) OR (isset($LOGO_UPLOAD_URL) AND $LOGO_UPLOAD_URL == 1) OR (isset($logos) AND $logos)}
<div class="imageTollBox">
    <!--<div class="upload_link">
        <span class="cpd_title">{l s='Tools' mod='customproductdesign'}</span>
        <div class="close-tooltip" title="{l s='Close' mod='customproductdesign'}" alt="{l s='close' mod='customproductdesign'}"></div>
        <div class="drag-toolbox" title="{l s='Move' mod='customproductdesign'}" alt="{l s='Move' mod='customproductdesign'}"></div>
    </div>-->

    <div class="image-upload-container">
        {include file='./url_form.tpl'}
        {if $LOGO_UPLOAD_EN_DS == 1 AND $LOGO_UPLOAD_URL == 1}
            <span class="or-sep">&nbsp;{l s='OR' mod='customproductdesign'}&nbsp;</span>
        {/if}
        {if isset($LOGO_UPLOAD_EN_DS) AND $LOGO_UPLOAD_EN_DS == 1}
            {assign var=params value=['action' => 'upload_logo']}
            <form id="logo-upload-front" class="form-logo-upload" method="post" action="{$cpd_link->getModuleLink('customproductdesign', 'cpdesign', $params)|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data">
                <p id="alert-1" class="alert alert-success" style="display:none;">{l s='Image uploaded successfully' mod='customproductdesign'}</p>
                <p id="alert-2" class="alert alert-error" style="display:none;">{l s='Operation failed' mod='customproductdesign'}</p>
                <p id="alert-3" class="alert alert-danger" style="display:none;">{l s='Invalid file' mod='customproductdesign'}</p>
                <input type="hidden" name="id_product" value="{$id_product_old|escape:'htmlall':'UTF-8'}">
                <div class="form-group" style="display:none;">
                    <div class="col-lg-7">
                        <input type="file" name="logo" id="logo" class="control-label">
                    </div>
                </div>
                <div class="bottom_buttons">
                    <div class="upload_container">
                        <a id="browse_logo" type="submit" class="c_p_d-button btn">
                            <i class="icon-upload-alt"></i> {l s='Upload from PC' mod='customproductdesign'}
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        {/if}
    </div>

    <!-- rotation -->
    <div class="rotation-wrapper">
        <span>{l s='Tag Rotation' mod='customproductdesign'}</span>
        <div id="rotate" data-angle="0"></div>
        <p><small>{l s='Select a tag to rotate.' mod='customproductdesign'}</small></p>
    </div>

    <!-- transparency -->
    <div class="transparency-wrapper">
        <span>{l s='Transparency' mod='customproductdesign'}</span>
        <input id="transparent" type="range" value="1" step="0.01" min="0" max="1.0" data-trans="10" data-rangeslider>
        <p><small>{l s='move slider to adjust transparency.' mod='customproductdesign'}</small></p>

        <script type="text/javascript">
            //<![CDATA[
            $(function() {
                $('#transparent').rangeslider({
                    polyfill: false,
                    rangeClass: 'font_rangeslider',
                    disabledClass: 'font_rangeslider--disabled',
                    horizontalClass: 'font_rangeslider--horizontal',
                    fillClass: 'font_rangeslider__fill',
                    handleClass: 'font_rangeslider__handle',
                    // Callback function
                    onSlide: function(position, value) {
                        var selector = $('#cpd_layers_section_content li.selected');
						var id = $(selector).attr('data-id-tag');
						var type = $(selector).attr('data-type');
						var id_design = $(selector).attr('data-id-design');
                            var parent = $( '#cpd_layer_top_' + id_design );
                            if ( type == 'image' || type == 'text' ) {
                                var idSelector = '#tag_' + type + '_' + id;
                                
                                //select editing side preview
                                if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                                    $( '#mini_thumb_' + id_design ).trigger('click');   
                                }
                                $( idSelector ).css( 'opacity', value );
                                $(this).attr('data-trans', value);
                            }
                        //}
                    },
                });
                // tag rotation
                 $( "#rotate" ).slider({
                    value: 0,
                    min: -20,
                    max: 20,
                    slide: function (event, ui) {
                        var angle = ui.value;
                        var prev_angle = $(this).attr('data-angle');
                        var selector = $('#cpd_layers_section_content li.selected');
						var id = $(selector).attr('data-id-tag');
						var type = $(selector).attr('data-type');
						var id_design = $(selector).attr('data-id-design');
                            var parent = $( '#cpd_layer_top_' + id_design );
                            if ( type == 'image' || type == 'text' ) {
                                var idSelector = '#tag_' + type + '_' + id;
                                //select editing side preview
                                if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                                    $( '#mini_thumb_' + id_design ).trigger('click');   
                                }
                                if (angle > prev_angle || (angle < 0 && prev_angle < 0 && Math.abs(prev_angle) > Math.abs(angle))) {
                                    rotateClockwise( idSelector, angle );
                                } else {
                                    rotateAnticlockwise( idSelector, angle )
                                }
                                $(this).attr('data-angle', angle);
                            }
                        //}
                    }
                });

                $('#transparent').rangeslider('update', true);
                $('input[name=logo]').on('change', function() {
                   $('form#logo-upload-front').submit();
                });
            });
            //]]>
        </script>
    </div>
    <!-- imagges list -->
    <div id="upload_logo_front">
    {if isset($logos) AND $logos}
    {if !empty($_tags)}
        <ol id="cpd_tags_filters">
            <li><a onclick="initTagOrigins(0, this);" class="active">{l s='all' mod='customproductdesign'}</a></li>
            {foreach from=$_tags item=tag}
            <li><a onclick="initTagOrigins('{$tag|escape:'htmlall':'UTF-8'}', this);">{$tag|escape:'htmlall':'UTF-8'}</a></li>
            {/foreach}
        </ol>
    {/if}
        {foreach from=$logos item=logo}
        {*if (!$logo.id_guest) OR (isset($logo.id_guest) AND $logo.id_guest == $_id_guest)*}
            <div logoname="{$logo.logo_name|escape:'htmlall':'UTF-8'}" data-tags="{$logo.tags|escape:'htmlall':'UTF-8'}" logoid="{$logo.id_logo|escape:'htmlall':'UTF-8'}" class="logo_container boxlogosq">
                <img src="{$logo.logo_path|escape:'htmlall':'UTF-8'}" class="logos" width="50px" height="50px" logo-name="{$logo.logo_name|escape:'htmlall':'UTF-8'}">
            </div>
        {*/if*}
        {/foreach}
    {/if}
    </div>
    <div id="acd-uploaded-img" class="img-container"></div>
</div>
{/if}