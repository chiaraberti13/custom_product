<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by Satoshi Brasileiro.
 *
 *  @author    Satoshi Brasileiro
 *  @copyright Satoshi Brasileiro 2021
 *  @license   Single domain
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_3_0($module)
{
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_customized_workplace (
        `id_product_customized_workplace` int(10) unsigned NOT NULL auto_increment,
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
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8'
    );
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates (
        `id_cpd_saved_templates`        int(10) unsigned NOT NULL auto_increment,
        `id_design`                     int(11) unsigned NOT NULL,
        `base_img`                      LONGTEXT,
        PRIMARY KEY                     (`id_cpd_saved_templates`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8'
    );
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_saved_templates_elements (
        `id_cpd_saved_templates_elements` int(10) unsigned NOT NULL auto_increment,
        `id_cpd_saved_templates`        int(11) unsigned NOT NULL,
        `id_element`                    int(11) unsigned NOT NULL,
        `child_style`                   text,
        `type`                          varchar(255),
        `style`                         LONGTEXT,
        `value`                         varchar(255),
        PRIMARY KEY                     (`id_cpd_saved_templates_elements`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8'
    );
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cpd_dynamic_pricing (
        `id_cpd_dynamic_pricing`        int(11) unsigned NOT NULL auto_increment,
        `qty_from`                      int(11) DEFAULT 0,
        `qty_to`                        int(11) DEFAULT 0,
        `price`                         DECIMAL(20,6) DEFAULT 0,
        PRIMARY KEY                     (`id_cpd_dynamic_pricing`)
        ) ENGINE=' . _MYSQL_ENGINE_ . '     AUTO_INCREMENT=1 DEFAULT CHARSET=utf8'
    );

    return true;
}
