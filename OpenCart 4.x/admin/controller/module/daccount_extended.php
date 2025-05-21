<?php
/**
 * Controller Module D.Account Extended Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Admin\Controller\Extension\DAccountExtended\Module;
class DAccountExtended extends \Opencart\System\Engine\Controller {
    private $error = array();

    public function index(): void {
        $x = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $this->load->language('extension/daccount_extended/module/daccount_extended');

        $this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_setting_module->addModule('daccount_extended.daccount_extended', $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module'));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        $url = '';

        if (isset($this->request->get['module_id'])) {
            $url .= '&module_id=' . $this->request->get['module_id'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/daccount_extended/module/daccount_extended', 'user_token=' . $this->session->data['user_token'] . $url)
        );

        $data['action'] = $this->url->link('extension/daccount_extended/module/daccount_extended' . $x . 'save', 'user_token=' . $this->session->data['user_token'] . $url);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        if (isset($this->request->get['module_id'])) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info['name'])) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['attr_ID'])) {
            $data['attr_ID'] = $this->request->post['attr_ID'];
        } elseif (!empty($module_info['attr_ID'])) {
            $data['attr_ID'] = $module_info['attr_ID'];
        } else {
            $data['attr_ID'] = '';
        }

        if (isset($this->request->post['links'])) {
            $data['links'] = $this->request->post['links'];
        } elseif (!empty($module_info['links'])) {
            $data['links'] = $module_info['links'];
        } else {
            $data['links'] = array();
        }

        if (isset($this->request->post['excluded'])) {
            $data['excluded'] = $this->request->post['excluded'];
        } elseif (!empty($module_info['excluded'])) {
            $data['excluded'] = $module_info['excluded'];
        } else {
            $data['excluded'] = array();
        }

        if (isset($this->request->post['invert'])) {
            $data['invert'] = $this->request->post['invert'];
        } elseif (!empty($module_info['invert'])) {
            $data['invert'] = $module_info['invert'];
        } else {
            $data['invert'] = false;
        }

        if (isset($this->request->post['customer_type'])) {
            $data['customer_type'] = (int)$this->request->post['customer_type'];
        } elseif (!empty($module_info['customer_type'])) {
            $data['customer_type'] = (int)$module_info['customer_type'];
        } else {
            $data['customer_type'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info['status'])) {
            $data['status'] = (int)$module_info['status'];
        } else {
            $data['status'] = 0;
        }

        $blog_exist = version_compare(VERSION, '4.1.0.0', '>=') ? true : false;

        $blog_pages = array();

        if ($blog_exist) {
            $blog_pages = array(
                'label' => $this->language->get('text_opt_blog'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_blog_a'),
                        'route' => 'cms/blog' . $x . 'info'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_blog_c'),
                        'route' => 'cms/blog'
                    )
                )
            );
        }

        $data['excluded_list'] = array(
            $blog_pages,

            array(
                'label' => $this->language->get('text_opt_general'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_home'),
                        'route' => 'common/home'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_mainten'),
                        'route' => 'common/maintenance'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_inform'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_article'),
                        'route' => 'information/information'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_contact'),
                        'route' => 'information/contact'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_sitemap'),
                        'route' => 'information/sitemap'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_prods'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_prod'),
                        'route' => 'product/product'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_cat'),
                        'route' => 'product/category'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_special'),
                        'route' => 'product/special'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_manufac'),
                        'route' => 'product/manufacturer'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_search'),
                        'route' => 'product/search'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_ch'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_ch_cart'),
                        'route' => 'checkout/cart'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_ch_ch'),
                        'route' => 'checkout/checkout'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_ch_succ'),
                        'route' => 'checkout/success'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_ch_err'),
                        'route' => 'checkout/failure'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_acc'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_acc_log'),
                        'route' => 'account/login'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_reg'),
                        'route' => 'account/register'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_frg'),
                        'route' => 'account/forgotten'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_rsc'),
                        'route' => 'account/success'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_acc'),
                        'route' => 'account/account'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_edt'),
                        'route' => 'account/edit'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_pch'),
                        'route' => 'account/password'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_shp'),
                        'route' => 'account/address'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_ord'),
                        'route' => 'account/order'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_rtn'),
                        'route' => 'account/returns'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_dld'),
                        'route' => 'account/download'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_rwd'),
                        'route' => 'account/reward'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_fin'),
                        'route' => 'account/transaction'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_rws'),
                        'route' => 'account/subscription'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_prn'),
                        'route' => 'account/affiliate'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_ref'),
                        'route' => 'account/tracking'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_wlt'),
                        'route' => 'account/wishlist'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_sss'),
                        'route' => 'account/newsletter'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_lsc'),
                        'route' => 'account/logout'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_acc_pac'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_acc_pog'),
                        'route' => 'affiliate/login'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_peg'),
                        'route' => 'affiliate/register'
                    ),
                    array(
                        'name'  => $this->language->get('text_opt_acc_psc'),
                        'route' => 'affiliate/success'
                    )
                )
            ),

            array(
                'label' => $this->language->get('text_opt_err'),
                'pages' => array(
                    array(
                        'name'  => $this->language->get('text_opt_err_nf'),
                        'route' => 'error/not_found'
                    )
                )
            )
        );

        $data['links_list'] = array(
            array(
                'name'  => $this->language->get('text_login'),
                'route' => 'account/login'
            ),
            array(
                'name'  => $this->language->get('text_register'),
                'route' => 'account/register'
            ),
            array(
                'name'  => $this->language->get('text_forgotten'),
                'route' => 'account/forgotten'
            ),
            array(
                'name'  => $this->language->get('text_account'),
                'route' => 'account/account'
            ),
            array(
                'name'  => $this->language->get('text_edit_acc'),
                'route' => 'account/edit'
            ),
            array(
                'name'  => $this->language->get('text_password'),
                'route' => 'account/password'
            ),
            array(
                'name'  => $this->language->get('text_address'),
                'route' => 'account/address'
            ),
            array(
                'name'  => $this->language->get('text_order'),
                'route' => 'account/order'
            ),
            array(
                'name'  => $this->language->get('text_return'),
                'route' => 'account/return'
            ),
            array(
                'name'  => $this->language->get('text_download'),
                'route' => 'account/download'
            ),
            array(
                'name'  => $this->language->get('text_reward'),
                'route' => 'account/reward'
            ),
            array(
                'name'  => $this->language->get('text_transaction'),
                'route' => 'account/transaction'
            ),
            array(
                'name'  => $this->language->get('text_wishlist'),
                'route' => 'account/wishlist'
            ),
            array(
                'name'  => $this->language->get('text_newsletter'),
                'route' => 'account/newsletter'
            ),
            array(
                'name'  => $this->language->get('text_logout'),
                'route' => 'account/logout'
            )
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/daccount_extended/module/daccount_extended', $data));
    }

    /**
     * Save Module Data.
     * 
     * @return void
     */
    public function save(): void {
        $this->load->language('extension/daccount_extended/module/daccount_extended');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/daccount_extended/module/daccount_extended')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if ((mb_strlen($this->request->post['name']) < 3) || (mb_strlen($this->request->post['name']) > 64)) {
            $json['error']['name'] = $this->language->get('error_name');
        }

        if (!$json) {
            $this->load->model('setting/module');

            if (!isset($this->request->get['module_id'])) {
                $this->model_setting_module->addModule('daccount_extended.daccount_extended', $this->request->post);

                //$json['redirect'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');
            } else {
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);

                //$json['redirect'] = $this->url->link('extension/daccount_extended/module/daccount_extended', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id']);
            }

            $json['success'] = $this->language->get('text_success');
        } else {
            if (!isset($json['error']['warning'])) {
                $json['error']['warning'] = $this->language->get('error_warning');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Validate Permission and fields.
     * 
     * @return bool $this->error
     */
    protected function validate(): bool {
        if (!$this->user->hasPermission('modify', 'extension/daccount_extended/module/daccount_extended')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((mb_strlen($this->request->post['name']) < 3) || (mb_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
}