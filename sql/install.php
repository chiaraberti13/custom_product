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

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized (
        `id_customized`                 int(11) NOT NULL auto_increment,
        `id_product`                    int(11) NOT NULL,
        `id_attribute_product`          int(11) DEFAULT 0,
        `active`                        tinyint(1) default \'0\',
        `path`                          TEXT,
        `width`                         DECIMAL(20,2),
        `height`                        DECIMAL(20,2),
        `left`                          DECIMAL(20,2),
        `top`                           DECIMAL(20,2),
        `position`                      int(11) NOT NULL,
        `date_add`                      datetime default NULL,
        `date_upd`                      datetime default NULL,
        PRIMARY KEY                     (`id_customized`)
       ) ENGINE=' . _MYSQL_ENGINE_ . '  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_lang (
        `id_customized`                 int(11) NOT NULL,
        `design_title`                  varchar(255),
        `id_lang`                       int(11) NOT NULL,
        PRIMARY KEY                     (`id_customized`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_settings (
        `id_settings`                   int(11) NOT NULL auto_increment,
        `id_product`                    int(11) NOT NULL,
        `id_attribute_product`          int(11) DEFAULT 0,
        `type`                          varchar(32),
        `value`                         TEXT,
        `date_add`                      datetime default NULL,
        `date_upd`                      datetime default NULL,
        PRIMARY KEY                     (`id_settings`)
       ) ENGINE=' . _MYSQL_ENGINE_ . '      AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_shop (
        `id_customized`                 int(11) NOT NULL,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                 int(11) unsigned NOT NULL Default 1,
        PRIMARY KEY                     (`id_customized`, `id_shop`)
       ) ENGINE=' . _MYSQL_ENGINE_ . '      AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_tags (
        `id_design_tag`                 int(10) unsigned NOT NULL auto_increment,
        `id_design`                     int(11) unsigned NOT NULL,
        `length`                        int(11) unsigned DEFAULT NULL,
        `active`                        tinyint(1) NOT NULL DEFAULT 1,
        `draggable`                     tinyint(2) NOT NULL DEFAULT 1,
        `resizable`                     tinyint(2) NOT NULL DEFAULT 1,
        `type`                          varchar(10) DEFAULT NULL,
        `price`                         DECIMAL(20,2) DEFAULT 0,
        `width`                         DECIMAL(20,2),
        `height`                        DECIMAL(20,2),
        `pos_x`                         DECIMAL(20,2),
        `pos_y`                         DECIMAL(20,2),
        `pos_top`                       DECIMAL(20,2),
        `pos_left`                      DECIMAL(20,2),
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_design_tag`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_workplace (
        `id_product_customized_workplace`                 int(10) unsigned NOT NULL auto_increment,
        `id_design`                     int(11) unsigned NOT NULL,
        `length`                        int(11) unsigned DEFAULT NULL,
        `active`                        tinyint(1) NOT NULL DEFAULT 1,
        `draggable`                     tinyint(2) NOT NULL DEFAULT 1,
        `resizable`                     tinyint(2) NOT NULL DEFAULT 1,
        `type`                          varchar(10) DEFAULT NULL,
        `width`                         DECIMAL(20,2),
        `height`                        DECIMAL(20,2),
        `pos_x`                         DECIMAL(20,2),
        `pos_y`                         DECIMAL(20,2),
        `pos_top`                       DECIMAL(20,2),
        `pos_left`                      DECIMAL(20,2),
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_product_customized_workplace`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates (
        `id_cpd_saved_templates`        int(10) unsigned NOT NULL auto_increment,
        `id_design`                     int(11) unsigned NOT NULL,
        `base_img`                      LONGTEXT,
        PRIMARY KEY                     (`id_cpd_saved_templates`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates_elements (
        `id_cpd_saved_templates_elements` int(10) unsigned NOT NULL auto_increment,
        `id_cpd_saved_templates`        int(11) unsigned NOT NULL,
        `id_element`                    int(11) unsigned NOT NULL,
        `child_style`                   text,
        `type`                          varchar(255),
        `style`                         LONGTEXT,
        `value`                         varchar(255),
        PRIMARY KEY                     (`id_cpd_saved_templates_elements`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_tags_lang (
        `id_design_tag`                 int(11) NOT NULL,
        `tag_title`                     varchar(255),
        `id_lang`                       int(11) NOT NULL,
        PRIMARY KEY                     (`id_design_tag`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'customized_cart_products (
        `id_custom_cart`                int(11) unsigned NOT NULL auto_increment,
        `id_customization`              int(11) DEFAULT 0,
        `cpd_id_product`                int(11) NOT NULL,
        `id_attribute_product`          int(11) DEFAULT 0,
        `parent`                        int(11) DEFAULT 0,
        `id_cart`                       int(11) NOT NULL,
        `id_order`                      int(11) DEFAULT 0,
        `id_customer`                   int(11) DEFAULT 0,
        `id_guest`                      int(11) DEFAULT 0,
        `cart_qty`                      int(11) DEFAULT 1,
        `price`                         DECIMAL(20,2),
        `currency`                      varchar(128) NOT NULL,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                 int(11) unsigned NOT NULL Default 1,
        `date_add`                      datetime default NULL,
        PRIMARY KEY                     (`id_custom_cart`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'fonts (
        `id_font`                       int(11) unsigned NOT NULL auto_increment,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                int(11) unsigned NOT NULL Default 1,
        `font_name`                     TEXT,
        `font_path`                     TEXT,
        `status`                        tinyint(1) NOT NULL DEFAULT 1,
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_font`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'colour (
        `id_colour`                     int(11) unsigned NOT NULL auto_increment,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                 int(11) unsigned NOT NULL Default 1,
        `colour_name`                   varchar(30) default NULL,
        `colour_code`                   varchar(12) NOT NULL,
        `status`                        tinyint(1) NOT NULL DEFAULT 1,
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_colour`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'logo (
        `id_logo`                       int(10) unsigned NOT NULL auto_increment,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                 int(11) unsigned NOT NULL Default 1,
        `id_guest`                      int(11) unsigned NOT NULL Default 0,
        `id_customer`                   int(11) unsigned NOT NULL Default 0,
        `logo_name`                     varchar(30) default NULL,
        `tags`                          varchar(255) default NULL,
        `logo_path`                     TEXT NULL,
        `status`                        tinyint(1) NOT NULL DEFAULT 1,
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_logo`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'material (
        `id_material`                   int(11) unsigned NOT NULL auto_increment,
        `id_shop`                       int(11) unsigned NOT NULL Default 1,
        `id_shop_group`                 int(11) unsigned NOT NULL Default 1,
        `material_name`                 varchar(30) NOT NULL,
        `price`                         DECIMAL(20,6) DEFAULT 0,
        `material_path`                 TEXT,
        `status`                        tinyint(1) NOT NULL DEFAULT 1,
        `date_add`                      datetime default NULL,
        `date_up`                       datetime default NULL,
        PRIMARY KEY                     (`id_material`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
$sql[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_dynamic_pricing (
        `id_cpd_dynamic_pricing`        int(11) unsigned NOT NULL auto_increment,
        `qty_from`                      int(11) DEFAULT 0,
        `qty_to`                        int(11) DEFAULT 0,
        `price`                         DECIMAL(20,6) DEFAULT 0,
        PRIMARY KEY                     (`id_cpd_dynamic_pricing`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
