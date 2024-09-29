<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @author    Satoshi Brasileiro
 * @copyright FMM Modules
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminDesignBulkController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $this->context = Context::getContext();
    }

    public function initContent()
    {
        parent::initContent();
        $this->content = $this->renderForm();
        $this->context->smarty->assign(['content' => $this->content]);
    }

    public function renderForm()
    {
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->l('Copy Product Settings'),
                'icon' => 'icon-cogs',
            ],
            'warning' => $this->l('Please go to Catalog => Products and edit any product to see the option to enable Custom Product Designs.'),
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Source Product ID'),
                    'name' => 'source',
                    'lang' => false,
                    'required' => false,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'desc' => $this->l('Copy this product settings, please only use single ID of product not multiples.'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Target Product IDs'),
                    'name' => 'target',
                    'lang' => false,
                    'required' => false,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'desc' => $this->l('Use comma to add multiple IDs like 29,32,289,755.'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Target Categories'),
                    'name' => 'categories',
                ],
            ],
            'description' => 'All settings of CPD from Source product will be copied to target product(s).',
            'submit' => [
                'title' => $this->l('Start Copying'),
            ],
        ];
        $categories = Category::getSimpleCategories($this->context->language->id);

        $this->context->smarty->assign([
            'categories' => $categories,
        ]);

        return parent::renderForm();
    }

    public function initProcess()
    {
        if (Tools::isSubmit('submitAddconfiguration')) {
            $source = (int) Tools::getValue('source');
            $target = Tools::getValue('target');
            $categories = Tools::getValue('category');
            if ($source <= 0) {
                $this->errors[] = $this->l('The source product ID cannot be empty, only use integer value.');
            } elseif (empty($target) && empty($categories)) {
                $this->errors[] = $this->l('The target product(s) cannot be empty.');
            }
        }

        return parent::initProcess();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::isSubmit('submitAddconfiguration')) {
            $source = (int) Tools::getValue('source');
            $target = Tools::getValue('target');
            $target = trim(str_replace(' ', '', $target));
            $categories = Tools::getValue('category');

            if ($source > 0) {
                if ((int) strpos($target, ',') > 0) {
                    $target_array = explode(',', $target);

                    foreach ($target_array as $targetid_product) {
                        $targetid_product = (int) $targetid_product;

                        // first delete previous settings if any
                        $sql = new DbQuery();
                        $sql->select('id_customized');
                        $sql->from('product_customized');
                        $sql->where('`id_product` = ' . (int) $targetid_product);

                        $id_customized = Db::getInstance()->getValue($sql);

                        if ($id_customized && $targetid_product != $source) {
                            Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized WHERE `id_customized` = ' . (int) $id_customized);
                            Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized_lang WHERE `id_customized` = ' . (int) $id_customized);

                            Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized_settings WHERE `id_product` = ' . (int) $targetid_product);
                        }

                        $this->copyDesignData($source, $targetid_product, $id_customized);
                    }
                }
            }

            if (!empty($categories)) {
                foreach ($categories as $cat) {
                    $fetch_category = new Category($cat, (int) $this->context->language->id);
                    $items = $fetch_category->getProducts((int) $this->context->language->id, 0, 10000, null, null, false, true, false, 1, false);
                    if (!empty($items)) {
                        foreach ($items as $item) {
                            $targetid_product = $item['id_product'];
                            // dump($targetid_product);
                            // first delete previous settings if any
                            $sql = new DbQuery();
                            $sql->select('id_customized');
                            $sql->from('product_customized');
                            $sql->where('`id_product` = ' . (int) $targetid_product);

                            $id_customized = Db::getInstance()->getValue($sql);

                            if ($id_customized && $targetid_product != $source) {
                                Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized WHERE `id_customized` = ' . (int) $id_customized);
                                Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized_lang WHERE `id_customized` = ' . (int) $id_customized);

                                Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'product_customized_settings WHERE `id_product` = ' . (int) $targetid_product);
                            }

                            $this->copyDesignData($source, $targetid_product, $id_customized);
                        }
                    }
                }
            }
            if (empty($this->errors)) {
                Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . $this->token);
            }
        }
    }

    public function copyDesignData($source, $targetid_product, $id_customized)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product_customized');
        $sql->where('`id_product` = ' . (int) $source);
        $alldata = Db::getInstance()->executeS($sql);

        $id_customized = $alldata[0]['id_customized'];
        $id_product = $targetid_product;
        $id_attribute_product = $alldata[0]['id_attribute_product'];
        $active = $alldata[0]['active'];
        $path = $alldata[0]['path'];
        $width = $alldata[0]['width'];
        $height = $alldata[0]['height'];
        $left = $alldata[0]['left'];
        $top = $alldata[0]['top'];
        $position = $alldata[0]['position'];
        $date_add = $alldata[0]['date_add'];
        $date_upd = $alldata[0]['date_upd'];

        Db::getInstance()->insert('product_customized', [
            'id_product' => pSQL($id_product),
            'id_attribute_product' => pSQL($id_attribute_product),
            'active' => pSQL($active),
            'path' => pSQL($path),
            'width' => pSQL($width),
            'height' => pSQL($height),
            'left' => pSQL($left),
            'top' => pSQL($top),
            'position' => pSQL($position),
            'date_add' => pSQL($date_add),
            'date_upd' => pSQL($date_upd),
        ]);

        $sec_sql = new DbQuery();
        $sec_sql->select('*');
        $sec_sql->from('product_customized_lang');
        $sec_sql->where('`id_customized` = ' . (int) $id_customized);
        $sec_alldata = Db::getInstance()->executeS($sec_sql);

        $design_title = $sec_alldata[0]['design_title'];
        $id_lang = $sec_alldata[0]['id_lang'];

        $cust_sql = new DbQuery();
        $cust_sql->select('id_customized');
        $cust_sql->from('product_customized');
        $cust_sql->where('`id_product` = ' . (int) $targetid_product);

        $newid_customized = Db::getInstance()->getValue($cust_sql);
        Db::getInstance()->insert('product_customized_lang', [
            'id_customized' => pSQL($newid_customized),
            'design_title' => pSQL($design_title),
            'id_lang' => pSQL($id_lang),
        ]);

        $thi_sql = new DbQuery();
        $thi_sql->select('*');
        $thi_sql->from('product_customized_settings');
        $thi_sql->where('`id_product` = ' . (int) $source);
        $thi_alldata = Db::getInstance()->executeS($thi_sql);

        foreach ($thi_alldata as $key => $value) {
            $id_settings = $value['id_settings'];
            $id_attribute_product = $value['id_attribute_product'];
            $type = $value['type'];
            $valuee = $value['value'];
            $date_add = $value['date_add'];
            $date_upd = $value['date_upd'];

            Db::getInstance()->insert('product_customized_settings', [
                'id_product' => pSQL($targetid_product),
                'id_attribute_product' => pSQL($id_attribute_product),
                'type' => pSQL($type),
                'value' => pSQL($valuee),
                'date_add' => $date_add,
                'date_upd' => pSQL($date_upd),
            ]);
        }

        return true;
    }
}
