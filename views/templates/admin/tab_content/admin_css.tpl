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

{literal}
<style type="text/css">
/*== PS 1.6 ==*/
 #product-design ul.tab { list-style:none; padding:0; margin:0}

 #product-design ul.tab li a {background-color: white;border: 1px solid #DDDDDD;display: block;margin-bottom: -1px;padding: 10px 15px;}
 #product-design ul.tab li a { display:block; color:#555555; text-decoration:none}
 #product-design ul.tab li a.selected { color:#fff; background:#5cb85c}

 #productdesign_toolbar { clear:both; padding-top:20px; overflow:hidden}

 #productdesign_toolbar .pageTitle { min-height:90px}

 #productdesign_toolbar ul { list-style:none; float:right}

 #productdesign_toolbar ul li { display:inline-block; margin-right:10px}

 #productdesign_toolbar ul li .toolbar_btn {background-color: white;border: 1px solid #CCCCCC;color: #555555;-moz-user-select: none;background-image: none;border-radius: 3px 3px 3px 3px;cursor: pointer;display: inline-block;font-size: 12px;font-weight: normal;line-height: 1.42857;margin-bottom: 0;padding: 8px 8px;text-align: center;vertical-align: middle;white-space: nowrap; }

 #productdesign_toolbar ul li .toolbar_btn:hover { background-color:#00AFF0 !important; color:#fff;}

 #productdesign_form .language_flags { display:none}
 form#productdesign_form {
    background-color: #ebedf4;
    border: 1px solid #ccced7;
    /*min-height: 404px;*/
    padding: 5px 10px 10px;
}
</style>
{/literal}
{if isset($fonts) AND $fonts}
    {literal}
        <style type="text/css">
        {/literal}{foreach from=$fonts item=font}{literal}
         @font-face {
            font-family: "{/literal}{$font.font_name|escape:'htmlall':'UTF-8'}{literal}";
            src: url("{/literal}{$font.font_path|escape:'htmlall':'UTF-8'}{literal}");
            }
        {/literal}{/foreach}{literal}
        </style>
    {/literal}
{/if}