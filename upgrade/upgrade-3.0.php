<?php
/**
 * 2007-2018 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Adapter\Cache\CacheClearer;

function upgrade_module_3_0($object)
{
    Configuration::deleteByName('FOOTER_CMS');
    Configuration::deleteByName('FOOTER_BLOCK_ACTIVATION');
    Configuration::deleteByName('FOOTER_POWEREDBY');
    Configuration::deleteByName('FOOTER_PRICE-DROP');
    Configuration::deleteByName('FOOTER_NEW-PRODUCTS');
    Configuration::deleteByName('FOOTER_BEST-SALES');
    Configuration::deleteByName('FOOTER_CONTACT');
    Configuration::deleteByName('FOOTER_SITEMAP');

    Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'cms_block_page`');

    $object->reset();

    //Clear Symfony cache to update routing rules
    $container = SymfonyContainer::getInstance();
    if (null !== $container) {
        /** @var CacheClearer $cacheClearer */
        $cacheClearer = $container->get('prestashop.adapter.cache_clearer');
        $cacheClearer->clearAllCaches();
    }

    return true;
}
