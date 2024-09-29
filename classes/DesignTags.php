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

class DesignTags extends ObjectModel
{
    public $id_design_tag;

    public $id_design;

    public $active = 1;

    public $type;

    public $price = 0;

    public $width = 16;

    public $height = 16;

    public $pos_top = 10;

    public $pos_left = 5;

    public $length;

    public $draggable = 1;

    public $resizable = 1;

    public $date_add;

    public $date_up;

    public $tag_title;

    public $pos_x;

    public $pos_y;

    public static $definition = [
        'table' => 'product_customized_tags',
        'primary' => 'id_design_tag',
        'multilang' => true,
        'fields' => [
            'id_design' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'price' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'draggable' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'resizable' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'length' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'tag_title' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'],
            'width' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'height' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'pos_x' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'pos_y' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'pos_top' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'pos_left' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'type' => ['type' => self::TYPE_STRING],
            'date_add' => ['type' => self::TYPE_DATE],
            'date_up' => ['type' => self::TYPE_DATE],
        ],
    ];

    public function __construct($id_design_tag = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_design_tag, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        if (!parent::add($autodate, $null_values)) {
            return false;
        }

        return true;
    }

    public function update($null_values = false)
    {
        if (parent::update($null_values)) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        if (parent::delete()) {
            return true;
        }

        return false;
    }

    public static function deleteByDesign($id_design)
    {
        if (!$id_design) {
            return false;
        }

        $sql = new DbQuery();
        $sql->type('DELETE');
        $sql->from(self::$definition['table']);
        $sql->where('id_design = ' . (int) $id_design);

        return (bool) Db::getInstance()->execute($sql);
    }

    public static function updateTagAttributes($id_tag, $data)
    {
        if (!$id_tag) {
            return false;
        }

        return (bool) Db::getInstance()->update(self::$definition['table'], $data, 'id_design_tag = ' . (int) $id_tag);
    }

    public static function findActiveWindow($id_design)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_product_customized_workplace`
            FROM `' . _DB_PREFIX_ . 'product_customized_workplace`
            WHERE `id_design` = ' . (int) $id_design);
    }

    public static function addWindow($id_design)
    {
        Db::getInstance()->insert('product_customized_workplace',
            [
                'id_design' => (int) $id_design,
                'type' => 'window']
        );
        $last_id = (int) Db::getInstance()->Insert_ID();

        return $last_id;
    }
}
