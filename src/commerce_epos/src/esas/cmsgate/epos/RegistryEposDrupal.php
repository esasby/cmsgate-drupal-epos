<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\epos;

use Drupal\Core\Url;
use esas\cmsgate\CmsConnectorDrupal;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\view\client\CompletionPanelEposDrupal;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormDrupal;

class RegistryEposDrupal extends RegistryEpos
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorDrupal();
        $this->paysystemConnector = new PaysystemConnectorEpos();
    }


    /**
     * Переопределение для упрощения типизации
     * @return RegistryEposDrupal
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @throws \Exception
     */
    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON,
            [
                ConfigFieldsEpos::shopName(),
                ConfigFieldsEpos::paymentMethodName(),
                ConfigFieldsEpos::paymentMethodNameWebpay(),
                ConfigFieldsEpos::paymentMethodDetails(),
                ConfigFieldsEpos::paymentMethodDetailsWebpay(),
                ConfigFieldsEpos::sandbox() //managed by drupal
            ]);
        $configForm = new ConfigFormDrupal(
            AdminViewFields::CONFIG_FORM_COMMON,
            $managedFields);
        $configForm->addPhoneFieldNameIfPresent();
        return $configForm;
    }

    function getUrlWebpay($orderWrapper)
    {
        return Url::fromRoute('<current>', [], ['absolute' => TRUE])->toString();
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "commerce_epos", // код должен совпадать с кодом решения в маркете (@id в Plugin\Commerce\PaymentGateway\xxx.php)
            new VersionDescriptor("2.15.0", "2022-01-12"),
            "Прием платежей через ЕРИП (сервис Epos)",
            "https://bitbucket.org/esasby/cmsgate-drupal-epos/src/master/",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }

    public function getCompletionPanel($orderWrapper)
    {
        return new CompletionPanelEposDrupal($orderWrapper);
    }


}