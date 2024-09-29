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

class ProductCustomization extends ObjectModel
{
    public $id;

    public $id_product;

    public $id_attribute_product = 0;

    public $type = '';

    public $value = '';

    public $date_add;

    public $date_up;

    public const CPD_MATERIAL = 1;
    public const CPD_PREVIEW = 2;

    public static $definition = [
        'table' => 'product_customized_settings',
        'primary' => 'id_settings',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'id_attribute_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'type' => ['type' => self::TYPE_STRING],
            'value' => ['type' => self::TYPE_STRING],
            'date_add' => ['type' => self::TYPE_DATE],
            'date_up' => ['type' => self::TYPE_DATE],
        ],
    ];

    public function __construct($id = null, $id_shop = null)
    {
        parent::__construct($id, null, $id_shop);
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation(self::$definition['table'], ['type' => 'shop']);
        }
    }

    public static function columnExist()
    {
        $columns = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM information_schema.columns
            WHERE table_schema = "' . _DB_NAME_ . '" AND table_name = "' . _DB_PREFIX_ . 'customization_field_lang"');
        if (isset($columns) && $columns) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == 'id_shop') {
                    return true;
                }
            }
        }

        return false;
    }

    public static function typeExist($id_product, $type = '')
    {
        if (!$id_product || empty($type)) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table']);
        $sql->where('`id_product` = ' . (int) $id_product);
        $sql->where('`type` = "' . pSQL($type) . '"');

        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function addSettings($data = [])
    {
        if (!$data) {
            return false;
        } else {
            return (bool) Db::getInstance()->insert(self::$definition['table'], $data);
        }
    }

    public static function updateSettings($id_product, $data = [], $type = '')
    {
        if (!$id_product || !$data || empty($type)) {
            return false;
        } else {
            return (bool) Db::getInstance()->update(self::$definition['table'], $data, 'id_product = ' . (int) $id_product . ' AND type = "' . pSQL($type) . '"');
        }
    }

    public static function deleteByType($id_product, $type = '')
    {
        if (!$id_product || empty($type)) {
            return false;
        } else {
            $where = 'id_product = ' . (int) $id_product . ' AND type = "' . pSQL($type) . '"';

            return (bool) Db::getInstance()->delete(self::$definition['table'], $where);
        }
    }

    public static function deleteProductSettings($id_product)
    {
        if (!$id_product) {
            return false;
        } else {
            $where = 'id_product = ' . (int) $id_product;

            return (bool) Db::getInstance()->delete(self::$definition['table'], $where);
        }
    }

    public static function getTypeByProduct($id_product, $type = '')
    {
        if (!$id_product || empty($type)) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('value');
        $sql->from(self::$definition['table']);
        $sql->where('`id_product` = ' . (int) $id_product);
        $sql->where('`type` = "' . pSQL($type) . '"');

        return Db::getInstance()->getValue($sql);
    }

    public static function deleteFontById($id_font)
    {
        $font = self::getFontById($id_font);
        if (isset($font) && $font) {
            $filename = _PS_MODULE_DIR_ . 'customproductdesign/data/fonts/' . pathinfo($font['font_path'], PATHINFO_BASENAME);
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'fonts WHERE `id_font` = ' . (int) $id_font;
            if (unlink($filename) && Db::getInstance()->Execute($sql)) {
                return true;
            }
        }

        return false;
    }

    public static function deleteFonts()
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'fonts';
        if (Db::getInstance()->Execute($sql)) {
            return true;
        }

        return false;
    }

    public static function deleteColorById($id_colour)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'colour WHERE `id_colour` = ' . (int) $id_colour;
        if (Db::getInstance()->Execute($sql)) {
            return true;
        }

        return false;
    }

    public static function deleteColours()
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'colour';
        if (Db::getInstance()->Execute($sql)) {
            return true;
        }

        return false;
    }

    public static function deleteLogoById($id_logo)
    {
        if (!$id_logo || !self::getLogoById($id_logo)) {
            return false;
        }

        $logo = self::getLogoById($id_logo);
        if (isset($logo) && $logo) {
            $filename = _PS_MODULE_DIR_ . 'customproductdesign/data/logo/' . pathinfo($logo['logo_path'], PATHINFO_BASENAME);
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'logo WHERE `id_logo` = ' . (int) $id_logo;
            if (unlink($filename) && Db::getInstance()->Execute($sql)) {
                return true;
            }
        }

        return false;
    }

    public static function deleteMaterialById($id_material)
    {
        if (!$id_material) {
            return false;
        }

        $material = self::getMaterialById($id_material);
        if (isset($material) && $material) {
            $filename = _PS_MODULE_DIR_ . 'customproductdesign/data/material/' . pathinfo($material['material_path'], PATHINFO_BASENAME);
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'material WHERE `id_material` = ' . (int) $id_material;
            if (unlink($filename) && Db::getInstance()->Execute($sql)) {
                return true;
            }
        }

        return false;
    }

    public static function removeMaterialPath($id_material)
    {
        if ($id_material) {
            $material = self::getMaterialById($id_material);
            $filename = _PS_MODULE_DIR_ . 'customproductdesign/data/material/' . (string) $material['material_name'];
            if ($filename && unlink($filename)) {
                return true;
            }

            return false;
        }
    }

    public static function deleteLogos()
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'logo';
        if (Db::getInstance()->Execute($sql)) {
            return true;
        }

        return false;
    }

    public static function addToCartExplict($data)
    {
        if (isset($data) && $data) {
            if (Db::getInstance()->insert('customized_cart_products', $data)) {
                return true;
            }
        }

        return false;
    }

    public static function isNonOrderedProducts($id_cart, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE id_order = 0
            AND id_cart = ' . (int) $id_cart . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return (bool) Db::getInstance()->ExecuteS($sql);
    }

    public static function isCustomOrderedProduct($id_cart, $id_order, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE id_cart = ' . (int) $id_cart . '
            AND id_order = ' . (int) $id_order . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return (bool) Db::getInstance()->ExecuteS($sql);
    }

    public static function getCustomOrderedProduct($id_cart, $id_order, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE id_cart = ' . (int) $id_cart . '
            AND id_order = ' . (int) $id_order . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function getCustomCartProducts($id_cart, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE id_cart = ' . (int) $id_cart . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return (bool) Db::getInstance()->ExecuteS($sql);
    }

    public static function getOldProducts($id_cart, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT `old_id_product`, `old_id_attribute_product`
            FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE id_cart = ' . (int) $id_cart . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function deleteCustomProduct($id_cart, $id_product, $id_product_attribute = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'customized_cart_products
            WHERE `id_cart` = ' . (int) $id_cart . '
            AND cpd_id_product = ' . (int) $id_product . '
            AND id_attribute_product = ' . (int) $id_product_attribute;

        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        if (Db::getInstance()->Execute($sql)) {
            return true;
        }

        return false;
    }

    public static function addFonts($font = [])
    {
        if (isset($font) && $font) {
            if (Db::getInstance()->insert('fonts', $font)) {
                return true;
            }
        }

        return false;
    }

    public static function addColor($color = [])
    {
        if (isset($color) && $color) {
            if ($color['id_colour'] > 0) {
                self::updateColour($color, $color['id_colour']);
            } else {
                if (Db::getInstance()->insert('colour', $color)) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function addLogo($logo = [])
    {
        if (isset($logo) && $logo) {
            if (Db::getInstance()->insert('logo', $logo)) {
                return Db::getInstance()->Insert_ID();
            }
        }

        return false;
    }

    public static function addMaterial($material = [])
    {
        if (isset($material) && $material) {
            if ($material['id_material'] > 0) {
                self::updateMaterial($material, $material['id_material']);
            } else {
                if (Db::getInstance()->insert('material', $material)) {
                    return Db::getInstance()->Insert_ID();
                }
            }
        }

        return false;
    }

    public static function updateFont($fonts = [], $id_font = 0)
    {
        if (isset($fonts) && $id_font) {
            foreach ($fonts as $font) {
                $sql = 'UPDATE ' . _DB_PREFIX_ . 'fonts
                    SET font_name = "' . pSQL($font['font_name']) . '",
                        fonth_path = "' . pSQL($font['fonth_path']) . '",
                        status = ' . (int) $font['status'] . ',
                        date_up = NOW()
                    WHERE id_font = ' . (int) $id_font;

                if (DB::getInstance(_PS_USE_SQL_SLAVE_)->Execute($sql)) {
                    return true;
                }

                return false;
            }
        }

        return false;
    }

    public static function updateColour($colour = [], $id_colour = 0)
    {
        if (isset($colour) && $id_colour) {
            if (DB::getInstance()->update('colour', $colour, 'id_colour = ' . (int) $id_colour)) {
                return true;
            }
        }

        return false;
    }

    public static function updateMaterial($material = [], $id_material = 0)
    {
        if (isset($material) && $id_material) {
            if (DB::getInstance()->update('material', $material, 'id_material = ' . (int) $id_material)) {
                return true;
            }
        }

        return false;
    }

    public static function updateLogo($logos = [], $id_logo = 0)
    {
        if (isset($logos) && $id_logo) {
            foreach ($logos as $logo) {
                $sql = 'UPDATE ' . _DB_PREFIX_ . 'logo
                    SET font_name = "' . pSQL($logo['font_name']) . '",
                        fonth_path = "' . pSQL($logo['fonth_path']) . '",
                        status = ' . (int) $logo['status'] . ',
                        date_up = NOW()
                    WHERE id_logo = ' . (int) $id_logo;

                if (DB::getInstance(_PS_USE_SQL_SLAVE_)->Execute($sql)) {
                    return true;
                }

                return false;
            }
        }

        return false;
    }

    public static function getProductCustomizationById($id_product, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if ($id_shop && Shop::isFeatureActive()) {
            $sql = 'SELECT pc.`id_product`, pcs.*
            FROM `' . _DB_PREFIX_ . 'product_customized` pc
            RIGHT JOIN `' . _DB_PREFIX_ . 'product_customized_shop` pcs ON (pc.`id_customized` = pcs.`id_customized`)
            WHERE pc.id_product = ' . (int) $id_product . '
            AND pcs.id_shop = ' . (int) $id_shop;
        } else {
            $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'product_customized WHERE id_product = ' . (int) $id_product;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function getFonts($id_shop = null, $active = false)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'fonts WHERE 1';
        if ($id_shop && Shop::isFeatureActive() && Shop::getContextShopGroupID()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        if ($active) {
            $sql .= ' AND status = ' . (int) $active;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getColors($id_shop = null, $active = false)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'colour WHERE 1';
        if ($id_shop && Shop::isFeatureActive() && Shop::getContextShopGroupID()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }
        if ($active) {
            $sql .= ' AND status = ' . (int) $active;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getLogos($id_shop = null, $active = false)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'logo WHERE 1';
        if ($id_shop && Shop::isFeatureActive() && Shop::getContextShopGroupID()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        if ($active) {
            $sql .= ' AND status = ' . (int) $active;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getMaterials($active = false, $id_product = null, $id_shop = null, $id_shop_group = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if (!$id_shop_group) {
            $id_shop = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'material WHERE 1';
        if ($active) {
            $sql .= ' AND status = ' . (int) $active;
            if ($id_product) {
                $selected_materials = ProductCustomization::getTypeByProduct($id_product, 'selected_materials');
                if (!$selected_materials || empty($selected_materials)) {
                    return false;
                } else {
                    $selected_materials = json_decode($selected_materials);
                    $sql .= ' AND id_material IN(' . implode(',', $selected_materials) . ')';
                }
            }
        }
        if ($id_shop && $id_shop_group && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop . ' AND id_shop_group = ' . (int) $id_shop_group;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getActiveFonts($id_product, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        $selected_fonts = ProductCustomization::getTypeByProduct($id_product, 'selected_fonts');
        if (!$id_product || !$selected_fonts || empty($selected_fonts)) {
            return false;
        } else {
            $selected_fonts = json_decode($selected_fonts);
        }
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'fonts WHERE status = 1 AND id_font IN(' . implode(',', $selected_fonts) . ')';
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getActiveColors($id_product, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        $selected_colors = ProductCustomization::getTypeByProduct($id_product, 'selected_colors');
        if (!$id_product || !$selected_colors || empty($selected_colors)) {
            return false;
        } else {
            $selected_colors = json_decode($selected_colors);
        }
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'colour WHERE status = 1 AND id_colour IN(' . implode(',', $selected_colors) . ')';
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getActiveLogos($id_product, $id_shop = null, $id_guest = 0)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        $selected_images = ProductCustomization::getTypeByProduct($id_product, 'selected_images');
        if (!$id_product || !$selected_images || empty($selected_images)) {
            return false;
        } else {
            $selected_images = json_decode($selected_images);
        }
        if ($id_guest > 0) {
            $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'logo WHERE status = 1 AND id_guest = ' . (int) $id_guest . ' OR id_guest = 0
            AND id_logo IN(' . implode(',', $selected_images) . ')';
        } else {
            $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'logo WHERE status = 1 AND id_logo IN(' . implode(',', $selected_images) . ')';
        }
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->executeS($sql);
    }

    public static function getColorById($id_color, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'colour WHERE id_colour = ' . (int) $id_color;
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function getFontById($id_font, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'fonts WHERE id_font = ' . (int) $id_font;
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function getLogoById($id_logo, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'logo WHERE id_logo = ' . (int) $id_logo;
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function getMaterialById($id_material, $id_shop = null, $id_shop_group = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'material WHERE id_material = ' . (int) $id_material;
        if ($id_shop && $id_shop_group && Shop::isFeatureActive()) {
            $sql .= ' AND id_shop = ' . (int) $id_shop . ' AND id_shop_group = ' . (int) $id_shop_group;
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function updateStatus($table = '', $primary_key = '', $id = 0, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if (!empty($table) && !empty($primary_key) && $id) {
            $sql = 'UPDATE ' . _DB_PREFIX_ . (string) $table . '
            SET status = !status
            WHERE ' . pSQL($primary_key) . ' = ' . (int) $id;

            if ($id_shop && Shop::isFeatureActive()) {
                $sql .= ' AND id_shop = ' . (int) $id_shop;
            }

            if (Db::getInstance()->Execute($sql)) {
                return true;
            }
        }

        return false;
    }

    public static function duplicateSingleAttributes($id_product_old, $id_product_new, $id_product_attribute_old)
    {
        $return = true;
        $combination_images = [];

        $result = Db::getInstance()->executeS('SELECT pa.*, product_attribute_shop.* FROM `' . _DB_PREFIX_ . 'product_attribute` pa ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
            WHERE pa.`id_product` = ' . (int) $id_product_old . ' AND pa.`id_product_attribute` = ' . (int) $id_product_attribute_old);
        $combinations = [];
        foreach ($result as $row) {
            $id_product_attribute_old = (int) $row['id_product_attribute'];
            if (empty($combinations[$id_product_attribute_old])) {
                $id_combination = null;
                $id_shop = null;
                $result2 = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_combination` WHERE `id_product_attribute` = ' . (int) $id_product_attribute_old);
            } else {
                $id_combination = (int) $combinations[$id_product_attribute_old];
                $id_shop = (int) $row['id_shop'];
                $context_old = Shop::getContext();
                $context_shop_id_old = Shop::getContextShopID();
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
            }

            $row['id_product'] = (int) $id_product_new;
            unset($row['id_product_attribute']);
            unset($row['id_shop']);

            $combination = new Combination($id_combination, null);
            foreach ($row as $k => $v) {
                $combination->$k = $v;
            }

            $combination->quantity = 1;
            $combination->default_on = 1;

            $return &= $combination->save();

            $id_product_attribute_new = (int) $combination->id;

            if ($result_images = self::getImageId($id_product_attribute_old)) {
                $combination_images['old'][$id_product_attribute_old] = $result_images;
                $combination_images['new'][$id_product_attribute_new] = $result_images;
            }

            if (empty($combinations[$id_product_attribute_old])) {
                $combinations[$id_product_attribute_old] = (int) $id_product_attribute_new;
                foreach ($result2 as $row2) {
                    $row2['id_product_attribute'] = (int) $id_product_attribute_new;
                    $return &= Db::getInstance()->insert('product_attribute_combination', $row2);
                }
            } else {
                Shop::setContext($context_old, $context_shop_id_old);
            }
        }

        $impacts = self::getAttributesImpacts($id_product_old);

        if (is_array($impacts) && count($impacts) && $impacts) {
            $impact_sql = 'INSERT INTO `' . _DB_PREFIX_ . 'attribute_impact` (`id_product`, `id_attribute`, `weight`, `price`) VALUES ';

            foreach ($impacts as $id_attribute) {
                $impact_sql .= '(' . (int) $id_product_new . ', ' . (int) $id_attribute . ', 0.0, 0.0),';
            }

            $impact_sql = substr_replace($impact_sql, '', -1);
            $impact_sql .= ' ON DUPLICATE KEY UPDATE `price` = VALUES(price), `weight` = VALUES(weight)';
            Db::getInstance()->execute($impact_sql);
        }

        return !$return ? false : $combination_images;
    }

    public static function getImageId($id_product_attribute)
    {
        if (!empty($id_product_attribute) && (int) $id_product_attribute) {
            $image = Db::getInstance()->getRow('SELECT id_image FROM ' . _DB_PREFIX_ . 'product_attribute_image WHERE id_product_attribute = ' . (int) $id_product_attribute);
        }

        return $image;
    }

    public static function getAttributeImage($id_product)
    {
        $sql = 'SELECT pi.`id_image`
            FROM ' . _DB_PREFIX_ . 'product_attribute_image pi
            LEFT JOIN ' . _DB_PREFIX_ . 'image i
            ON(i.id_image = pi.id_image)
            WHERE i.id_product = ' . (int) $id_product;

        $result = Db::getInstance()->ExecuteS($sql);
        if (!empty($result)) {
            $result = array_shift($result);
        } else {
            $result = Product::getCover((int) $id_product);
        }

        return $result;
    }

    public static function getAttributesImpacts($id_product)
    {
        $return = [];
        $result = Db::getInstance()->executeS('SELECT ai.`id_attribute`, ai.`price`, ai.`weight` FROM `' . _DB_PREFIX_ . 'attribute_impact` ai
            WHERE ai.`id_product` = ' . (int) $id_product);

        if (!$result) {
            return [];
        }
        foreach ($result as $impact) {
            $return[$impact['id_attribute']]['price'] = (float) $impact['price'];
            $return[$impact['id_attribute']]['weight'] = (float) $impact['weight'];
        }

        return $return;
    }

    public static function getOrderIdByProduct($id_product, $id_shop = null)
    {
        if (!$id_product) {
            return false;
        }
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        return Db::getInstance()->getValue('SELECT `id_order` FROM `' . _DB_PREFIX_ . 'order_detail`
            WHERE `product_id` = ' . (int) $id_product . ' GROUP BY id_order');
    }

    public static function isExists($id_product, $id_shop = null)
    {
        $table = self::$definition['table'];
        if (!$id_product) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . pSQL($table) . '` p';
        $where = 'p.id_product = ' . (int) $id_product;
        if ($id_shop && Shop::isFeatureActive()) {
            $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . pSQL($table) . '_shop` ps ON (p.id_customized = ps.id_customized)';
            $where .= ' AND ps.id_shop = ' . (int) $id_shop;
        }
        $sql .= ' WHERE ' . $where;

        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function isColorAttributeGroup($id_attribute_group)
    {
        if (!$id_attribute_group) {
            return false;
        }

        return (bool) Db::getInstance()->getRow('SELECT `group_type`
            FROM `' . _DB_PREFIX_ . 'attribute_group`
            WHERE `id_attribute_group` = ' . (int) $id_attribute_group . '
            AND group_type = \'color\'');
    }

    public static function getAttributeColor($id_attribute_group, $id_attribute)
    {
        if (!$id_attribute_group || !$id_attribute) {
            return false;
        }

        return Db::getInstance()->getValue('SELECT `color`
            FROM `' . _DB_PREFIX_ . 'attribute`
            WHERE `id_attribute_group` = ' . (int) $id_attribute_group . '
            AND id_attribute = ' . (int) $id_attribute);
    }

    public static function getIdProductAttributesByIdAttributes($id_product, $id_attributes, $find_best = false)
    {
        if (!is_array($id_attributes)) {
            return 0;
        }

        $id_product_attribute = Db::getInstance()->getValue('
        SELECT pac.`id_product_attribute`
        FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
        INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
        WHERE id_product = ' . (int) $id_product . ' AND id_attribute IN (' . implode(',', array_map('intval', $id_attributes)) . ')
        GROUP BY id_product_attribute
        HAVING COUNT(id_product) = ' . count($id_attributes));

        if ($id_product_attribute === false && $find_best) {
            // find the best possible combination
            // first we order $id_attributes by the group position
            $orderred = [];
            $result = Db::getInstance()->executeS('SELECT `id_attribute` FROM `' . _DB_PREFIX_ . 'attribute` a
            INNER JOIN `' . _DB_PREFIX_ . 'attribute_group` g ON a.`id_attribute_group` = g.`id_attribute_group`
            WHERE `id_attribute` IN (' . implode(',', array_map('intval', $id_attributes)) . ') ORDER BY g.`position` ASC');

            foreach ($result as $row) {
                $orderred[] = $row['id_attribute'];
            }

            while ($id_product_attribute === false && count($orderred) > 0) {
                array_pop($orderred);
                $id_product_attribute = Db::getInstance()->getValue('
                SELECT pac.`id_product_attribute`
                FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                WHERE id_product = ' . (int) $id_product . ' AND id_attribute IN (' . implode(',', array_map('intval', $orderred)) . ')
                GROUP BY id_product_attribute
                HAVING COUNT(id_product) = ' . count($orderred));
            }
        }

        return $id_product_attribute;
    }

    public static function getAttributesInformationsByProduct($id_product)
    {
        $result = Db::getInstance()->executeS('
        SELECT DISTINCT a.`id_attribute`, a.`id_attribute_group`, al.`name` as `attribute`, agl.`name` as `group`, pa.`default_on` as `default`
        FROM `' . _DB_PREFIX_ . 'attribute` a
        LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al
            ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) Context::getContext()->language->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl
            ON (a.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int) Context::getContext()->language->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac
            ON (a.`id_attribute` = pac.`id_attribute`)
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa
            ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
        ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
        ' . Shop::addSqlAssociation('attribute', 'pac') . '
        WHERE pa.`id_product` = ' . (int) $id_product);

        return $result;
    }

    public static function getDesignPrice($id_product, $id_attribute_product = 0, $id_cart = 0, $id_customization = 0)
    {
        if (!$id_product) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('price');
        $sql->from('customized_cart_products');
        $sql->where('`cpd_id_product` = ' . (int) $id_product);
        $sql->where('`id_cart` = ' . (int) $id_cart);
        $sql->where('`id_customization` = ' . (int) $id_customization);
        $sql->where('`id_attribute_product` = ' . (int) $id_attribute_product);

        return (float) Db::getInstance()->getValue($sql);
    }

    public static function deleteCartCustomization($id_product, $id_attribute_product = 0, $id_cart = 0, $id_customization = 0)
    {
        if (!$id_product) {
            return false;
        }

        $where = '`cpd_id_product` = ' . (int) $id_product;

        if ($id_attribute_product) {
            $where .= ' AND `id_attribute_product` = ' . (int) $id_attribute_product;
        }

        if ($id_cart) {
            $where .= ' AND `id_cart` = ' . (int) $id_cart;
        }

        if ($id_customization) {
            $where .= ' AND `id_customization` = ' . (int) $id_customization;
        }

        return (bool) Db::getInstance()->delete('customized_cart_products', $where);
    }

    public static function customizationExists($id_customer, $id_cart, $id_product = 0, $id_attribute_product = 0, $id_customization = 0, $id_shop = 0, $id_shop_group = 0)
    {
        if (!$id_customer || !$id_cart) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customized_cart_products');
        $sql->where('`id_customer` = ' . (int) $id_customer);
        $sql->where('`id_cart` = ' . (int) $id_cart);

        if ($id_product) {
            $sql->where('`cpd_id_product` = ' . (int) $id_product);
        }

        if ($id_attribute_product) {
            $sql->where('`id_attribute_product` = ' . (int) $id_attribute_product);
        }

        if ($id_customization) {
            $sql->where('`id_customization` = ' . (int) $id_customization);
        }

        if (Shop::isFeatureActive()) {
            $sql->where('`id_shop` = ' . (int) $id_shop);
            $sql->where('`id_shop_group` = ' . (int) $id_shop_group);
        }

        return (bool) Db::getInstance()->executeS($sql);
    }

    public static function checkCustomization($id_cart, $id_customization, $id_shop = 0, $id_shop_group = 0)
    {
        if (!$id_customization || !$id_cart) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customized_cart_products');
        $sql->where('`id_customization` = ' . (int) $id_customization);
        $sql->where('`id_cart` = ' . (int) $id_cart);

        if ($id_customization) {
            $sql->where('`id_customization` = ' . (int) $id_customization);
        }

        if (Shop::isFeatureActive()) {
            $sql->where('`id_shop` = ' . (int) $id_shop);
            $sql->where('`id_shop_group` = ' . (int) $id_shop_group);
        }

        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function setOrderId($id_customer, $id_cart, $id_order, $id_shop = 0, $id_shop_group = 0)
    {
        if (!$id_customer || !$id_cart || !$id_order) {
            return false;
        }

        $where = 'id_customer = ' . (int) $id_customer . ' AND id_cart = ' . (int) $id_cart;
        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        if (Shop::isFeatureActive()) {
            $where .= ' AND `id_shop` = ' . (int) $id_shop;
            $where .= ' AND `id_shop_group` = ' . (int) $id_shop_group;
        }

        return (bool) Db::getInstance()->update('customized_cart_products', ['id_order' => $id_order], $where);
    }

    public static function isCustomFieldExist($cpd_type)
    {
        if (!$cpd_type) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customization_field');
        $sql->where('`cpd_type` = ' . (int) $cpd_type);

        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function getCustomField($cpd_type)
    {
        if (!$cpd_type) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('id_customization_field');
        $sql->from('customization_field');
        $sql->where('`cpd_type` = ' . (int) $cpd_type);

        return (int) Db::getInstance()->getValue($sql);
    }

    public static function getSettingsByType($id_product, $type)
    {
        $valid = ['status', 'selected_images', 'selected_fonts', 'selected_colors', 'selected_materials'];
        if (!$id_product || !isset($type) || !in_array($type, $valid)) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product_customized_settings');
        $sql->where('`id_product` = ' . (int) $id_product);
        $sql->where('`type` = "' . pSQL($type) . '"');

        return Db::getInstance()->getRow($sql);
    }

    public static function getIdCustomization($id_product, $id_product_attribute = 0, $id_cart = 0)
    {
        if (!$id_product) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('id_customization');
        $sql->from('customization');
        $sql->where('`id_product` = ' . (int) $id_product);
        $sql->where('`id_product_attribute` = ' . (int) $id_product_attribute);
        $sql->where('`id_cart` = ' . (int) $id_cart);

        return Db::getInstance()->getValue($sql);
    }

    public static function getParent($id_product, $id_product_attribute = 0, $id_shop = 0, $id_shop_group = 0)
    {
        if (!$id_product) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }
        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = new DbQuery();
        $sql->select('parent');
        $sql->from('customized_cart_products');
        $sql->where('`cpd_id_product` = ' . (int) $id_product);

        if ($id_product_attribute) {
            $sql->where('`id_product_attribute` = ' . (int) $id_product_attribute);
        }

        if (Shop::isFeatureActive()) {
            $sql->where('`id_shop` = ' . (int) $id_shop);
            $sql->where('`id_shop_group` = ' . (int) $id_shop_group);
        }

        return (int) Db::getInstance()->getValue($sql);
    }

    public static function getProductCustomization($id_product, $id_attribute_product = 0, $id_cart = 0, $id_shop = null, $id_shop_group = null)
    {
        if (!$id_product) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        if (!$id_shop_group) {
            $id_shop_group = (int) Context::getContext()->shop->id_shop_group;
        }

        $sql = new DbQuery();
        $sql->select('cd.`id_customization`');
        $sql->from('customized_cart_products', 'cd');
        $sql->leftJoin('customization', 'c', 'cd.cpd_id_product = c.id_product AND cd.id_attribute_product = c.id_product_attribute AND cd.id_cart = c.id_cart');

        $sql->where('cd.cpd_id_product = ' . (int) $id_product);

        if ($id_attribute_product) {
            $sql->where('cd.id_attribute_product = ' . (int) $id_attribute_product);
        }

        if ($id_cart) {
            $sql->where('cd.id_cart = ' . (int) $id_cart);
        }

        if (Shop::isFeatureActive()) {
            $sql->where('cd.id_shop = ' . (int) $id_shop);
            $sql->where('cd.id_shop_group = ' . (int) $id_shop_group);
        }

        return Db::getInstance()->getRow($sql);
    }

    public static function updateCustomerLogo($data = [], $where = null)
    {
        if (!$where || !$data) {
            return false;
        } else {
            return (bool) Db::getInstance()->update('logo', $data, $where);
        }
    }

    public static function getGuestId($id_customer)
    {
        if (!Validate::isUnsignedId($id_customer)) {
            return false;
        }

        return (int) Db::getInstance()->getValue('SELECT `id_guest`
            FROM `' . _DB_PREFIX_ . 'guest` WHERE `id_customer` = ' . (int) $id_customer
        );
    }

    public static function getCustomerLogo($id_customer, $id_shop = null)
    {
        if (!$id_customer) {
            return false;
        }

        if (!$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('logo');
        $sql->where('id_customer = ' . (int) $id_customer);
        if ($id_shop && Shop::isFeatureActive()) {
            $sql->where('id_shop = ' . (int) $id_shop);
        }

        return Db::getInstance()->executeS($sql);
    }

    public static function getDynamicPrices()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('cpd_dynamic_pricing');

        return Db::getInstance()->executeS($sql);
    }

    public static function deleteTemplateById($id)
    {
        Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'cpd_saved_templates WHERE `id_cpd_saved_templates` = ' . (int) $id);
        Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'cpd_saved_templates_elements WHERE `id_cpd_saved_templates` = ' . (int) $id);

        return true;
    }

    public static function getTagsForImgs($id_product)
    {
        $selected_images = ProductCustomization::getTypeByProduct($id_product, 'selected_images');
        if (!$id_product || !$selected_images || empty($selected_images)) {
            return null;
        } else {
            $selected_images = json_decode($selected_images);
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT GROUP_CONCAT(`tags`) as tgs FROM ' . _DB_PREFIX_ . 'logo WHERE status = 1
                                           AND id_logo IN(' . implode(',', $selected_images) . ')');
    }
}
