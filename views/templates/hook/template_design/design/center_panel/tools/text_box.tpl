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

{if (isset($fonts) AND $fonts|@count > 0) OR (isset($colors) AND $colors|@count > 0)}
	<div id="text-panel" data-accordion>
		<!--<div id="text" class="text_box">
			<span class="cpd_title">{l s='Tools' mod='customproductdesign'}</span>
			<div class="close-tooltip" title="{l s='Close' mod='customproductdesign'}" alt="{l s='close' mod='customproductdesign'}"></div>
			<div class="drag-toolbox" title="{l s='Move' mod='customproductdesign'}" alt="{l s='Move' mod='customproductdesign'}"></div>
		</div>-->
		<div class="text-tool-container dspl-table">
			<!-- fonts -->
			{if isset($fonts) AND $fonts|@count > 0}
				<div class="fonts-wrapper formating">
					<span class="clearfix">{l s='Fonts' mod='customproductdesign'}</span>
					<span class="font-selector-container">
						{include file='./fonts.tpl'}
					</span>
				</div>
			{/if}
			<!-- fonts size -->
			<div class="size-wrapper formating">
				<span>{l s='Fonts Size' mod='customproductdesign'}</span>
				<!-- <input id="fonts-size" type="hidden" value="12"> -->
				<input id="font-siz" type="range" value="12" step="1" min="8" max="80" data-rangeslider>
				<p><small>{l s='Select a text tag and adjust fonts size.' mod='customproductdesign'}</small></p>
			</div>

			<!-- alignment -->
			{if isset($fonts) AND $fonts|@count > 0}
				<div class="formating">
					<div class="text-align-wrapper cpd_left_block">
						<span>{l s='Text Alignment' mod='customproductdesign'}</span>
						<div id="text-alignment" class="text-align-element">
							<input id="align-left" name="text_align" class="txt-align" type="radio" value="left">
							<label class="align-left" for="align-left"><span class="cpd_align" data-align="left"></span></label>
							<input id="align-center" name="text_align" class="txt-align" type="radio" value="center">
							<label class="align-center" for="align-center"><span class="cpd_align" data-align="center"></span></label>
							<input id="align-right" name="text_align" class="txt-align" type="radio" value="right">
							<label class="align-right" for="align-right"><span class="cpd_align" data-align="right"></span></label>
						</div>
					</div>
					<!-- weight -->
					<div class="text-align-wrapper cpd_right_block">
						<span>{l s='Font Style' mod='customproductdesign'}</span>
						<div id="fonts-style" class="text-align-element">
							<input id="fonts-bold" value="bold" class="txt-align" type="checkbox">
							<label class="fonts-bold" for="fonts-bold"><span class="cpd_fonts_style" data-style="bold" data-parent="fonts-bold"></span></label>
							<input id="fonts-italic" value="italic" class="txt-align" type="checkbox">
							<label class="fonts-italic" for="fonts-italic"><span class="cpd_fonts_style" data-style="italic" data-parent="fonts-italic"></span></label>
							<input id="fonts-underline" value="underline" class="txt-align" type="checkbox">
							<label class="fonts-underline" for="fonts-underline"><span class="cpd_fonts_style" data-style="underline" data-parent="fonts-underline"></span></label>
						</div>
					</div>
				</div>
			{/if}

			<!-- colors -->
			{if isset($colors) AND $colors|@count > 0}
				<div class="color-wrapper formating">
					<span class="clearfix">{l s='Text Colors' mod='customproductdesign'}</span>
					<span>
						{include file='./colors.tpl'}
					</span>
				</div>
			{/if}
			
			<!-- rotation -->
		    <div class="rotation-wrapper formating">
		        <span>{l s='Tag Rotation' mod='customproductdesign'}</span>
		        <div id="rotate" data-angle="0"></div>
		        <p><small>{l s='Select a tag to rotate.' mod='customproductdesign'}</small></p>
		    </div>

			<!-- transparency -->
			<div class="transparency-wrapper formating">
				<span>{l s='Transparency' mod='customproductdesign'}</span>
				<input id="transparent" type="range" value="1" step="0.01" min="0" max="1.0" data-trans="10" data-rangeslider>
				<p><small>{l s='move slider to adjust transparency.' mod='customproductdesign'}</small></p>
				<script type="text/javascript">
		            //<![CDATA[
		            $(document).ready(function() {
		            	var id_tag = parseInt("{$id_tag|escape:'htmlall':'UTF-8'}");
		                var tag = $('#tag_text_' + id_tag);
		                $.fn.mColorPicker.defaults.imageFolder = "{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/customproductdesign/views/img/";

		            	$.fn.mColorPicker.closePicker = function () {
					        $(".mColor, .mPastColor, #mColorPickerInput, #mColorPickerWrapper").unbind();
					        $("#mColorPickerBg").hide();
					        $("#mColorPicker").fadeOut();

					        var selectedColor = $.fn.mColorPicker.RGBtoHex($.fn.mColorPicker.color);
					        $( '#icp_color_0' ).attr( 'data-colorcode', selectedColor );
					        setColor($( "#icp_color_0" ));
					        $( '.normal' ).each(function() {
					            $( "#icp_color_0" ).removeClass( 'highlighted' );
					        });
					        $( "#icp_color_0" ).find( '.normal' ).addClass( 'highlighted' );
					    };
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
		                       // if (typeof selectedTag[0] !== 'undefined' && selectedTag[0]) {
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

		                $('#font-siz').rangeslider({
					        polyfill: false,
					        // Default CSS classes
					        rangeClass: 'font_rangeslider',
					        disabledClass: 'font_rangeslider--disabled',
					        horizontalClass: 'font_rangeslider--horizontal',
					        fillClass: 'font_rangeslider__fill',
					        handleClass: 'font_rangeslider__handle',

					        // Callback function
					        onInit: function() {
							  if (typeof tag.attr('data-size') !== 'undefined' && tag.attr('data-size')) {
							  	$('#font-siz').val(tag.attr('data-size'))
							  }
					          $rangeEl = this.$range;
					          // add value label to handle
					          var $handle = $rangeEl.find('.font_rangeslider__handle');
					          var handleValue = '<div class="font_rangeslider__handle__value" style="display:none;">' + this.value + '</div>';
					          $handle.append(handleValue);
					        },

					        // Callback function
					        onSlide: function(position, value) {
					          var $handle = this.$range.find('.font_rangeslider__handle__value');
					          $handle.text(this.value).show();
					          setFonts(this.value);
					        },

					        onSlideEnd: function(position, value) {
					            $rangeEl = this.$range;
					            var $handle = this.$range.find('.font_rangeslider__handle__value');
					            $handle.hide();
					        }
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
		            });
		            //]]>
		        </script>
			</div>
		</div>
	</div>
{/if}