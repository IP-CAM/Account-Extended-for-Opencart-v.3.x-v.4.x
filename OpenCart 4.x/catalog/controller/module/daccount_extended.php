<?php
/**
 * Controller Module D.Account Extended Class
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

namespace Opencart\Catalog\Controller\Extension\DAccountExtended\Module;
class DAccountExtended extends \Opencart\System\Engine\Controller {
    public function index($setting) {
        $display = $setting['invert'] ? false : true;

        if (isset($this->request->get['route'])) {
            $route = (string)$this->request->get['route'];
        } else {
            $route = 'common/home';
        }

        if (!empty($setting['excluded'])) {
            $excluded_arr = $setting['excluded'];
        } else {
            $excluded_arr = array();
        }

        foreach ($excluded_arr as $value) {
            $tmp = str_replace('/', '\/', $value);

            if (preg_match("/^" . $tmp . "/", $route)) {
                $display = $setting['invert'] ? true : false;
                break;
            }
        }

        if (!empty($setting['links'])) {
            $data['links'] = $setting['links'];
        } else {
            $data['links'] = array();
            $display = false;
        }

        if ($setting['status'] && $display) {
            $data['logged'] = $this->customer->isLogged();
            $data['customer_type'] = $setting['customer_type'];

            if (($data['customer_type'] == 0) || ($data['logged'] && $data['customer_type'] == 1) || (!$data['logged'] && $data['customer_type'] == 2)) {
                $this->load->language('extension/daccount_extended/module/daccount_extended');

                static $module = 0;

                $data['attr_ID'] = $setting['attr_ID'];
                $data['route_current'] = $route;

                $data['account_links'] = array(
                    array(
                        'name'   => $this->language->get('text_login'),
                        'link'   => $this->url->link('account/login', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/login',
                        'class'  => 'login',
                        'logged' => false
                    ),
                    array(
                        'name'   => $this->language->get('text_register'),
                        'link'   => $this->url->link('account/register', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/register',
                        'class'  => 'register',
                        'logged' => false
                    ),
                    array(
                        'name'   => $this->language->get('text_forgotten'),
                        'link'   => $this->url->link('account/forgotten', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/forgotten',
                        'class'  => 'forgotten',
                        'logged' => false
                    ),
                    array(
                        'name'   => $this->language->get('text_account'),
                        'link'   => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/account',
                        'class'  => 'account',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_edit'),
                        'link'   => $this->url->link('account/edit', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/edit',
                        'class'  => 'edit',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_password'),
                        'link'   => $this->url->link('account/password', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/password',
                        'class'  => 'password',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_address'),
                        'link'   => $this->url->link('account/address', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/address',
                        'class'  => 'address',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_order'),
                        'link'   => $this->url->link('account/order', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/order',
                        'class'  => 'order',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_return'),
                        'link'   => $this->url->link('account/return', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/returns',
                        'class'  => 'return',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_download'),
                        'link'   => $this->url->link('account/download', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/download',
                        'class'  => 'download',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_reward'),
                        'link'   => $this->url->link('account/reward', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/reward',
                        'class'  => 'reward',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_transaction'),
                        'link'   => $this->url->link('account/transaction', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/transaction',
                        'class'  => 'transaction',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_subscriptn'),
                        'link'   => $this->url->link('account/subscription', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/subscription',
                        'class'  => 'subscription',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_affiliate'),
                        'link'   => $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/affiliate',
                        'class'  => 'affiliate',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_affiliate_t'),
                        'link'   => $this->url->link('account/tracking', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/tracking',
                        'class'  => 'tracking',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_wishlist'),
                        'link'   => $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/wishlist',
                        'class'  => 'wishlist',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_newsletter'),
                        'link'   => $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : '')),
                        'route'  => 'account/newsletter',
                        'class'  => 'newsletter',
                        'logged' => true
                    ),
                    array(
                        'name'   => $this->language->get('text_logout'),
                        'link'   => $this->url->link('account/logout', 'language=' . $this->config->get('config_language')),
                        'route'  => 'account/logout',
                        'class'  => 'logout',
                        'logged' => true
                    )
                );

                $display = false;

                foreach($data['account_links'] as $link) {
                    if ($data['logged']) {
                        if ($link['logged'] && in_array($link['route'], $data['links'])) {
                            $display = true;
                            break;
                        }
                    } else {
                        if (!$link['logged'] && in_array($link['route'], $data['links'])) {
                            $display = true;
                            break;
                        }
                    }
                }

                if ($display) {
                    $data['module'] = $module++;
                    return $this->load->view('extension/daccount_extended/module/daccount_extended', $data);
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}