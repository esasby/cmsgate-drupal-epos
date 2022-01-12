<?php

namespace Drupal\commerce_epos\Plugin\Commerce\PaymentGateway;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/init.php');

use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\ManualPaymentGatewayInterface;
use esas\cmsgate\drupal\CmsgatePaymentBase;
use esas\cmsgate\epos\controllers\ControllerEposAddInvoice;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPage;
use esas\cmsgate\Registry;
use Exception;

/**
 * Provides the Epos payment gateway.
 * ATTENTION! id must be equals to Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName()
 * @CommercePaymentGateway(
 *   id = "commerce_epos",
 *   label = "Epos (ERIP gate)",
 *   display_label = "Epos",
 *   forms = {
 *     "add-payment-method" = "Drupal\commerce_epos\PluginForm\EposForm",
 *   },
 * )
 */
class Epos extends CmsgatePaymentBase implements ManualPaymentGatewayInterface
{
    public function buildPaymentInstructions(PaymentInterface $payment)
    {
        // к сожалению, сгенерировать таким образом итоговый экран не получается, т.к. в этом случае используется
        // стандартый twig-шаблон, в котором переменная payment_instructions помещается внутри html-form и поэтому
        // внутренняя webpay-форма не отображается корректно
//        $orderWrapper = Registry::getRegistry()->getOrderWrapper($payment->getOrderId());
//        $controller = new ControllerEposCompletionPage();
//        $completionPanel = $controller->process($orderWrapper);
        $instructions = [
            '#type' => 'processed_text',
//            '#text' => $completionPanel->__toString(),
            '#text' => '',
            '#format' => 'full_html', // basic_html full_html
        ];
        return $instructions;
    }

    /**
     * Creates a payment.
     *
     * @param \Drupal\commerce_payment\Entity\PaymentInterface $payment
     *   The payment.
     * @param bool $capture
     *   Whether the created payment should be captured (VS authorized only).
     *   Allowed to be FALSE only if the plugin supports authorizations.
     *
     * @throws \InvalidArgumentException
     *   If $capture is FALSE but the plugin does not support authorizations.
     * @throws \Drupal\commerce_payment\Exception\PaymentGatewayException
     *   Thrown when the transaction fails f
     * or any reason.
     */
    public function createPayment(PaymentInterface $payment, $capture = TRUE)
    {
        try {
            $this->assertPaymentState($payment, ['new']); //todo check
            $payment->save();
            $orderWrapper = Registry::getRegistry()->getOrderWrapper($payment->getOrderId());
            // проверяем, привязан ли к заказу extId, если да,
            // то счет не выставляем, а просто прорисовываем старницу
            if (empty($orderWrapper->getExtId())) {
                $controller = new ControllerEposAddInvoice();
                $controller->process($orderWrapper);
            }
        } catch (Exception $e) {
            \Drupal::messenger()->addMessage($e->getMessage(), 'error', TRUE);
        }
    }
}
