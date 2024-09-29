<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by Satoshi Brasileiro.
 *
 *  @author    Satoshi Brasileiro
 *  @copyright Satoshi Brasileiro 2024
 *  @license   Single domain
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = [];

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_shop';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_settings';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_lang';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_layers';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_layers_lang';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'customized_cart_products';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_tags';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_tags_lang';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'fonts';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'colour';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'logo';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'material';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_customized_workplace';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates';

$sql[] = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates_elements';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
