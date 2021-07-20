<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AlternateHreflang;

use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;

class AlternateHreflang extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'alternatehreflang';

    const CONFIG_KEY_HREFLANG_FORMAT = "hreflangFormat";
    const CONFIG_HREFLANG_FORMAT_DEFAULT_VALUE = 0; // 0 for xx-xx or 1 for xx

    /**
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null): void
    {
        $this->checkConfigurationsValues();
    }

    public function update($currentVersion, $newVersion, ConnectionInterface $con = null): void
    {
        parent::update($currentVersion, $newVersion, $con);

        $this->checkConfigurationsValues();
    }

    protected function checkConfigurationsValues()
    {
        if (null === self::getConfigValue(self::CONFIG_KEY_HREFLANG_FORMAT)) {
            self::setConfigValue(
                self::CONFIG_KEY_HREFLANG_FORMAT,
                self::CONFIG_HREFLANG_FORMAT_DEFAULT_VALUE
            );
        }
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR . ucfirst(self::getModuleCode()). "/I18n/*"])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
