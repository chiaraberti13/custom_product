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

class CustomProductDesignerController extends ModuleAdminController
{
    protected $allowed_types = ['data:image/png;base64,', 'data:image/jpg;base64,', 'data:image/jpeg;base64,'];

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    public function postProcess()
    {
        $function = (string) Tools::getValue('action');
        switch ($function) {
            case 'addTag':
                $this->addTag();
                break;
            case 'saveDesignCover':
                $this->saveDesignCover();
                break;
            case 'updateDesignTitle':
                $this->updateDesignTitle();
                break;
            case 'ajaxUpdateTag':
                $this->ajaxUpdateTag();
                break;
            case 'changeDesignStatus':
                $this->changeDesignStatus();
                break;
            case 'setDesignTitle':
                $this->setDesignTitle($function);
                break;
            case 'updateTag':
                $this->updateTag($function);
                break;
            case 'setTagAttributes':
                $this->setTagAttributes();
                break;
            case 'setDesignAttributes':
                $this->setDesignAttributes();
                break;
            case 'updatePosition':
                $this->updatePosition();
                break;
            case 'getGroupForm':
                $this->getGroupForm($function);
                break;
            case 'removeDesign':
                $this->removeDesign();
                break;
            case 'removePremade':
                $this->removePremade();
                break;
            case 'removeTag':
                $this->removeTag();
                break;
            case 'removeWindowLayer':
                $this->removeWindowLayer();
                break;
            case 'addWindowLayer':
                $this->addWindowLayer();
                break;
            case 'setWindowAttributes':
                $this->setWindowAttributes();
                break;
                // setWindowAttributes
        }
        parent::postProcess();
    }

    protected function getGroupForm($action)
    {
        $id_employee = (int) Tools::getValue('id_employee');
        $result = ['hasError' => false, 'msg' => ''];
        $params = ['id_product' => (int) Tools::getValue('id_product'), 'id_employee' => $id_employee];
        if (method_exists($this->module, $action)) {
            $result = call_user_func([$this->module, $action], $params);
            $result['msg'] = $this->l('Operation Successful.');
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Invalid action.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function removeDesign()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $id_design = (int) Tools::getValue('id_design');
        if (!Validate::isLoadedObject($design = new Designer((int) $id_design))) {
            $result['hasError'] = true;
            $result['msg'] = $this->l('error: design object not found.');
        } elseif (!$design->delete()) {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Deletion failed.');
        } else {
            // remove associated images/tags
            DesignTags::deleteByDesign($id_design);
            $result['msg'] = $this->l('Design successfully deleted.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function removeWindowLayer()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $id_window = (int) Tools::getValue('id_window');
        // remove workplace
        Db::getInstance()->delete(
            'product_customized_workplace',
            '`id_product_customized_workplace` = ' . (int) $id_window
        );
        $result['msg'] = $this->l('Workplace successfully deleted.');
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function removeTag()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $id_tag = (int) Tools::getValue('id_tag');
        if (!$id_tag || !Validate::isLoadedObject($tag = new DesignTags((int) $id_tag))) {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Error: Tag object not found.');
        } elseif (!$tag->delete()) {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Deletion failed.');
        } else {
            $result['msg'] = $this->l('Tag successfully deleted.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function changeDesignStatus()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $id_design = (int) Tools::getValue('id_design');
        if (!Validate::isLoadedObject($design = new Designer((int) $id_design))) {
            $result['hasError'] = true;
            $result['msg'] = $this->l('error: design object not found.');
        } else {
            $design->active = !$design->active;
            if (!$design->update()) {
                $result['hasError'] = true;
                $result['msg'] = $this->l('Unsuccessful update.');
            } else {
                $result['status'] = $design->active;
                $result['msg'] = $this->l('Status successfully updated.');
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function setDesignTitle($action)
    {
        $result = ['hasError' => false, 'msg' => '', 'html' => ''];
        $params = ['id_design' => (int) Tools::getValue('id_design')];
        if (method_exists($this->module, $action)) {
            $form = call_user_func([$this->module, $action], $params);
            $result['html'] = $form;
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Invalid action.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function updateTag($action)
    {
        $result = ['hasError' => false, 'msg' => '', 'html' => ''];
        $params = ['id_tag' => (int) Tools::getValue('id_tag')];
        if (method_exists($this->module, $action)) {
            $form = call_user_func([$this->module, $action], $params);
            $result['html'] = $form;
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->l('Invalid action.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function setTagAttributes()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $postValues = CpdTools::getAllValues();

        $id_tag = (int) $postValues['id_tag'];
        unset($postValues['id_tag']);
        unset($postValues['action']);
        unset($postValues['controller']);
        unset($postValues['token']);
        unset($postValues['isolang']);
        unset($postValues['controllerUri']);

        if (DesignTags::updateTagAttributes($id_tag, $postValues)) {
            $result['hasError'] = false;
            $result['msg'] = $this->l('successful update.');
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->l('operation failed.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function setDesignAttributes()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $postValues = CpdTools::getAllValues();

        $id_design = (int) $postValues['id_design'];
        unset($postValues['id_design']);
        unset($postValues['action']);
        unset($postValues['controller']);
        unset($postValues['token']);
        unset($postValues['isolang']);
        unset($postValues['controllerUri']);

        if (Designer::updateAttributes($id_design, $postValues)) {
            $result['hasError'] = false;
            $result['msg'] = $this->l('successful update.');
        } else {
            $result['hasError'] = true;
            $result['msg'] = $this->l('operation failed.');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function setWindowAttributes()
    {
        $result = ['hasError' => false, 'msg' => '', 'pos' => ''];
        $id_window = (int) Tools::getValue('id_window');
        $pos_top = Tools::getValue('pos_top');
        $pos_left = Tools::getValue('pos_left');
        $width = Tools::getValue('width');
        $height = Tools::getValue('height');
        if (isset($width) && $width > 0) {
            Db::getInstance()->update('product_customized_workplace',
                [
                    'pos_top' => pSQL($pos_top),
                    'pos_left' => pSQL($pos_left),
                    'width' => pSQL($width),
                    'height' => pSQL($height)], 'id_product_customized_workplace = ' . (int) $id_window
            );
        } else {
            Db::getInstance()->update('product_customized_workplace',
                [
                    'pos_top' => pSQL($pos_top),
                    'pos_left' => pSQL($pos_left)], 'id_product_customized_workplace = ' . (int) $id_window
            );
        }
        $result['hasError'] = false;
        $result['pos'] = $pos_top;
        $result['msg'] = $this->l('successful update.');
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function updateDesignTitle()
    {
        $resp_msg = [];
        $languages = Language::getLanguages(false);
        $id_lang = (int) $this->context->language->id;
        $id_design = (int) Tools::getValue('id_design');
        $result = ['hasError' => false, 'msg' => '', 'title' => $this->l('Design Title'), 'id_design' => $id_design];
        if (!$id_design || !Validate::isLoadedObject($design = new Designer((int) $id_design))) {
            $resp_msg[] = $this->l('Designer object not found');
        } else {
            foreach ($languages as $lang) {
                if (!Validate::isGenericName(Tools::getValue('design_title_' . $lang['id_lang']))) {
                    $result['hasError'] = true;
                    $resp_msg[] = sprintf($this->l('Inavlid design title in %s'), Language::getIsoById($lang['id_lang']));
                } else {
                    $design->design_title[$lang['id_lang']] = pSQL(Tools::getValue('design_title_' . $lang['id_lang']));
                }
            }

            if ($result['hasError'] || !$design->update()) {
                $result['hasError'] = true;
                $resp_msg[] = $this->l('Unsuccessful Designer object update');
            } else {
                $result['hasError'] = false;
                $result['title'] = $design->design_title[$id_lang];
                $resp_msg[] = $this->l('Designer title successfully updated.');
            }
            $result['msg'] = implode('<br>', $resp_msg);
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    protected function ajaxUpdateTag()
    {
        $resp_msg = [];
        $languages = Language::getLanguages(false);
        $id_tag = (int) Tools::getValue('id_tag');
        $result = ['hasError' => false, 'msg' => '', 'price' => Tools::displayPrice(0), 'id_tag' => $id_tag];
        if (!$id_tag || !Validate::isLoadedObject($design_tag = new DesignTags((int) $id_tag))) {
            $resp_msg[] = $this->l('object not found');
        } else {
            foreach ($languages as $lang) {
                if (!Validate::isGenericName(Tools::getValue('tag_title_' . $lang['id_lang']))) {
                    $result['hasError'] = true;
                    $resp_msg[] = sprintf($this->l('Inavlid tag title in %s'), Language::getIsoById($lang['id_lang']));
                } else {
                    $design_tag->tag_title[$lang['id_lang']] = pSQL(Tools::getValue('tag_title_' . $lang['id_lang']));
                }
            }

            if (Tools::getValue('price') && !Validate::isPrice(Tools::getValue('price'))) {
                $result['hasError'] = true;
                $resp_msg[] = sprintf($this->l('Inavlid tag price %f'), Tools::getValue('price'));
            } else {
                $design_tag->price = (float) Tools::getValue('price');
            }

            if (Tools::getValue('length') && !Validate::isUnsignedInt(Tools::getValue('length'))) {
                $result['hasError'] = true;
                $resp_msg[] = sprintf($this->l('Inavlid tag font size %d'), Tools::getValue('length'));
            } else {
                $design_tag->length = (int) Tools::getValue('length');
            }

            $design_tag->draggable = (int) Tools::getValue('draggable');
            $design_tag->resizable = (int) Tools::getValue('resizable');
            if ($result['hasError'] || !$design_tag->update()) {
                $result['hasError'] = true;
                $resp_msg[] = $this->l('Unsuccessful operation');
            } else {
                $result['hasError'] = false;
                $result['price'] = Tools::displayPrice($design_tag->price);
                $resp_msg[] = $this->l('successfully updated.');
            }
            $result['msg'] = implode('<br>', $resp_msg);
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }

    public function addTag()
    {
        $return = ['hasError' => false, 'msg' => '', 'id_tag' => 0];
        $postValues = CpdTools::getAllValues();
        if (isset($postValues) && $postValues) {
            $id_design = (int) $postValues['id_design'];
            $tag_type = pSQL($postValues['tag_type']);
            if (isset($id_design) && !empty($tag_type)) {
                $tag = new DesignTags();
                $tag->id_design = $id_design;
                $tag->type = $tag_type;
                if (!$tag->add()) {
                    $return['hasError'] = true;
                    $return['msg'] = $this->l('Error tag creation.');
                } else {
                    $return['msg'] = $this->l('Tag successfully created.');
                    $return['id_tag'] = $tag->id;
                }
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($return)));
        } else {
            exit(Tools::jsonEncode($return));
        }
    }

    public function addWindowLayer()
    {
        $return = ['hasError' => false, 'msg' => '', 'id_window' => 0];
        $postValues = CpdTools::getAllValues();
        if (isset($postValues) && $postValues) {
            $id_design = (int) $postValues['id_design'];
            $tag_type = pSQL($postValues['tag_type']);
            if (isset($id_design) && !empty($tag_type)) {
                $window = new DesignTags();
                $findActiveWindow = (int) $window->findActiveWindow($id_design);
                if ($findActiveWindow > 0) {
                    $return['hasError'] = true;
                    $return['msg'] = $this->l('The design already have workplace layer.');
                } else {
                    $id_design = (int) $window->addWindow($id_design);
                    $return['msg'] = $this->l('Workplace successfully created.');
                    $return['id_window'] = $id_design;
                }
                // $tag->id_design = $id_design;
                // $tag->type = $tag_type;
                // if (!$tag->add()) {
                //    $return['hasError'] = true;
                //    $return['msg'] = $this->l('Error tag creation.');
                // } else {
                //    $return['msg'] = $this->l('Tag successfully created.');
                //    $return['id_window'] = $tag->id;
                // }
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($return)));
        } else {
            exit(Tools::jsonEncode($return));
        }
    }

    public function saveDesignCover()
    {
        $postValues = CpdTools::getAllValues();
        $return = ['hasError' => false, 'msg' => ''];
        if (isset($postValues) && $postValues) {
            $id_design = (int) $postValues['id_design'];
            $design = new Designer($id_design);
            switch ($postValues['type']) {
                case 'url':
                    $design->path = $postValues['source'];
                    break;

                case 'base64':
                    $image_path = _PS_UPLOAD_DIR_ . 'cpd' . DIRECTORY_SEPARATOR;
                    if (!file_exists($image_path)) {
                        @mkdir($image_path, 0777, true);
                    }

                    $image_path = $image_path . $id_design . DIRECTORY_SEPARATOR;
                    if (!file_exists($image_path)) {
                        @mkdir($image_path, 0777, true);
                    }

                    $filename = Tools::passwdGen(8);

                    $content = '';
                    $new_path = $image_path . $filename;
                    $content = str_replace($this->allowed_types, '', $postValues['source']);

                    $imgdata = base64_decode($content);
                    $info = finfo_open();
                    $mime_type = explode('/', finfo_buffer($info, $imgdata, FILEINFO_MIME_TYPE));

                    $filename = $filename . '.' . $mime_type[1];
                    $new_path = $new_path . '.' . $mime_type[1];

                    $uri_path = Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'upload' . DIRECTORY_SEPARATOR . 'cpd' . DIRECTORY_SEPARATOR . $design->id . DIRECTORY_SEPARATOR . $filename;

                    file_put_contents($new_path, base64_decode($content));
                    $design->path = $uri_path;
                    break;
            }

            // update path in db
            if (!$design->update()) {
                $return['hasError'] = true;
                $return['msg'] = $this->l('Error updating design cover.');
            } else {
                $return['hasError'] = false;
                $return['msg'] = $this->l('Cover updated successfully.');
            }
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($return)));
        } else {
            exit(Tools::jsonEncode($return));
        }
    }

    public function updatePosition()
    {
        $return = ['hasError' => true, 'msg' => $this->l('updated error')];
        if (Tools::getValue('cpd_groups_wrapper')) {
            $cpd_groups_wrapper = Tools::getValue('cpd_groups_wrapper');
            foreach ($cpd_groups_wrapper as $position => $id_design) {
                Db::getInstance()->update(
                    'product_customized',
                    [
                        'position' => (int) $position,
                    ],
                    'id_customized = ' . (int) $id_design
                );
            }
            $return['hasError'] = false;
            $return['msg'] = $this->l('Position successfully updated');
        }
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($return)));
        } else {
            exit(Tools::jsonEncode($return));
        }
    }

    protected function removePremade()
    {
        $result = ['hasError' => false, 'msg' => ''];
        $id_temp = (int) Tools::getValue('id_temp');
        // remove associated images/tags
        ProductCustomization::deleteTemplateById($id_temp);
        $result['msg'] = $this->l('Template successfully deleted.');
        if (Tools::version_compare(_PS_VERSION_, '8.0.0', '>=')) {
            exit($this->ajaxRender(json_encode($result)));
        } else {
            exit(Tools::jsonEncode($result));
        }
    }
}
