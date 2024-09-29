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

class Designer extends ObjectModel
{
    public $id_customized;

    public $id_product;

    public $id_attribute_product = 0;

    public $active = 1;

    public $path;

    public $width = 100;

    public $height = 100;

    public $left = 0;

    public $top = 2;

    public $position = 0;

    public $design_title;

    public $date_add;

    public $date_upd;

    public static $definition = [
        'table' => 'product_customized',
        'primary' => 'id_customized',
        'multilang' => true,
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'id_attribute_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'path' => ['type' => self::TYPE_STRING],
            'width' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'height' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'left' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'top' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'position' => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'design_title' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'],
            'date_add' => ['type' => self::TYPE_DATE],
            'date_upd' => ['type' => self::TYPE_DATE],
        ],
    ];

    protected static $fileds = [
        'id_customized',
        'id_product',
        'id_attribute_product',
        'active',
        'path',
        'width',
        'height',
        'left',
        'top',
        'position',
        'design_title',
        'date_add',
        'date_upd',
    ];

    public function __construct($id_customized = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_customized, $id_lang, $id_shop);
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation(self::$definition['table'], ['type' => 'shop']);
        }
    }

    public function add($autodate = true, $null_values = false)
    {
        if ($this->position <= 0) {
            $this->position = Designer::getMaxPosition($this->id_product);
        }

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

    public static function getProductDesigns($id_product, $id_lang = null, $active = true, $order_by = 'position')
    {
        if (!$id_product) {
            return false;
        }
        if (!$id_lang) {
            return Context::getContext()->language->id;
        }

        $order_by = (!empty($order_by) && in_array($order_by, self::$fileds)) ? $order_by : 'position';
        $sql = new DbQuery();
        $sql->select('c.`id_customized`');
        $sql->from(self::$definition['table'], 'c');
        if (Shop::isFeatureActive()) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'cs',
                'c.id_customized = cs.id_customized AND cs.id_shop IN (' . implode(', ', Shop::getContextListShopID()) . ')'
            );
        }
        $sql->where(($active) ? 'active = 1' : '1');
        $sql->where('c.id_product = ' . (int) $id_product);
        $sql->orderBy('c.' . pSQL($order_by));
        $results = Db::getInstance()->executeS($sql);

        $designs = null;
        if (isset($results) && $results) {
            foreach ($results as $key => $result) {
                $designs[$key]['designs'] = new Designer($result['id_customized'], ($active) ? $id_lang : null);
                $designs[$key]['tags'] = Designer::getDesignsById($result['id_customized'], $id_lang, $active);
            }
        }

        return $designs;
    }

    public static function getDesignsById($id_customized, $id_lang = null, $check_lang = true)
    {
        if (!$id_customized) {
            return false;
        }
        if (!$id_lang) {
            return Context::getContext()->language->id;
        }

        $sql = new DbQuery();
        $sql->select('t.*');
        $sql->from(self::$definition['table'] . '_tags', 't');

        if ($check_lang) {
            $sql->select('tl.*');
            $sql->leftJoin(
                self::$definition['table'] . '_tags_lang',
                'tl',
                't.active = 1 AND t.id_design_tag = tl.id_design_tag AND tl.id_lang = ' . (int) $id_lang
            );
        }
        $sql->where('t.id_design = ' . (int) $id_customized);

        return Db::getInstance()->executeS($sql);
    }

    public static function getDesignsByIdForTmp($id_customized, $id_lang = null, $check_lang = true, $idt)
    {
        if (!$id_customized) {
            return false;
        }
        if (!$id_lang) {
            return Context::getContext()->language->id;
        }

        $sql = new DbQuery();
        $sql->select('t.*');
        $sql->from(self::$definition['table'] . '_tags', 't');

        if ($check_lang) {
            $sql->select('tl.*');
            $sql->leftJoin(
                self::$definition['table'] . '_tags_lang',
                'tl',
                't.active = 1 AND t.id_design_tag = tl.id_design_tag AND tl.id_lang = ' . (int) $id_lang
            );
        }
        $sql->select('tm.*');
        $sql->leftJoin(
            'cpd_saved_templates_elements',
            'tm',
            't.id_design_tag = tm.id_element AND tm.id_cpd_saved_templates = ' . (int) $idt
        );
        $sql->where('t.id_design = ' . (int) $id_customized);

        return Db::getInstance()->executeS($sql);
    }

    public static function updateAttributes($id_design, $data)
    {
        if (!$id_design) {
            return false;
        }

        return (bool) Db::getInstance()->update(self::$definition['table'], $data, 'id_customized = ' . (int) $id_design);
    }

    public static function getMaxPosition($id_product)
    {
        $sql = new DbQuery();
        $sql->select('MAX(`position`)');
        $sql->from(self::$definition['table']);
        $sql->where('id_product = ' . (int) $id_product);
        $position = Db::getInstance()->getValue($sql);

        return ((is_numeric($position)) ? $position : 0) + 1;
    }

    public static function updateDesignPosition($id_customized, $position)
    {
        if (!$id_customized || !$position) {
            return false;
        }

        Db::getInstance()->update(
            self::$definition['table'],
            [
                'position' => (int) $position,
            ],
            'id_customized = ' . (int) $id_customized
        );
    }

    public static function getAllTemplates()
    {
        return Db::getInstance()->executeS('SELECT *
        FROM `' . _DB_PREFIX_ . 'cpd_saved_templates`');
    }

    public static function getAllTemplatesRelatively($id_product)
    {
        return Db::getInstance()->executeS('SELECT cst.*
        FROM `' . _DB_PREFIX_ . 'cpd_saved_templates` cst
        LEFT JOIN `' . _DB_PREFIX_ . 'product_customized` pc ON (cst.id_design = pc.id_customized)
        WHERE pc.id_product = ' . (int) $id_product);
    }

    public static function getProductDesignsForTemplate($id_product, $id_lang = null, $idd, $idt)
    {
        $order_by = 'position';
        $active = true;
        if (!$id_lang) {
            return Context::getContext()->language->id;
        }

        $order_by = (!empty($order_by) && in_array($order_by, self::$fileds)) ? $order_by : 'position';
        $sql = new DbQuery();
        $sql->select('c.`id_customized`');
        $sql->from(self::$definition['table'], 'c');
        if (Shop::isFeatureActive()) {
            $sql->leftJoin(
                self::$definition['table'] . '_shop',
                'cs',
                'c.id_customized = cs.id_customized AND cs.id_shop IN (' . implode(', ', Shop::getContextListShopID()) . ')'
            );
        }
        $sql->where(($active) ? 'active = 1' : '1');
        $sql->where('c.id_customized = ' . (int) $idd);
        $sql->orderBy('c.' . pSQL($order_by));
        $results = Db::getInstance()->executeS($sql);

        $designs = null;
        if (isset($results) && $results) {
            foreach ($results as $key => $result) {
                $designs[$key]['designs'] = new Designer($result['id_customized'], ($active) ? $id_lang : null);
                $designs[$key]['tags'] = Designer::getDesignsByIdForTmp($result['id_customized'], $id_lang, $active, $idt);
            }
        }

        return $designs;
    }

    public function getTagText($id, $idt)
    {
        return Db::getInstance()->getRow('SELECT *
                FROM `' . _DB_PREFIX_ . 'cpd_saved_templates_elements`
                WHERE `id_element` = ' . (int) $id, ' AND `id_cpd_saved_templates` = ' . (int) $idt);
    }

    public function getProductDesignLayersColl($id, $id_lang)
    {
        $tags = [];
        $results = self::getProductDesigns($id, $id_lang);
        if (isset($results) && $results) {
            foreach ($results as $result) {
                $designs = $result['tags'];
                foreach ($designs as $design) {
                    array_push($tags, $design);
                }
            }
        }

        return $tags;
    }

    public function getProductDesignLayersCollFiltered($id, $id_lang, $idd)
    {
        $tags = [];
        $results = self::getProductDesigns($id, $id_lang);
        if (isset($results) && $results) {
            foreach ($results as $result) {
                $designs = $result['tags'];
                foreach ($designs as $design) {
                    if ($design['id_design'] == $idd) {
                        array_push($tags, $design);
                    }
                }
            }
        }

        return $tags;
    }
}
