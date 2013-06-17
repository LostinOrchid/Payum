<?php
namespace Payum\Payex;

use Payum\Payment;
use Payum\Extension\EndlessCycleDetectorExtension;
use Payum\Payex\Action\Api\AutoPayAgreementAction;
use Payum\Payex\Action\Api\CheckAgreementAction;
use Payum\Payex\Action\Api\CompleteOrderAction;
use Payum\Payex\Action\Api\CreateAgreementAction;
use Payum\Payex\Action\Api\DeleteAgreementAction;
use Payum\Payex\Action\Api\InitializeOrderAction;
use Payum\Payex\Action\PaymentDetailsCaptureAction;
use Payum\Payex\Action\PaymentDetailsStatusAction;
use Payum\Payex\Action\AgreementDetailsStatusAction;
use Payum\Payex\Action\AutoPayCaptureAction;
use Payum\Payex\Api\AgreementApi;
use Payum\Payex\Api\OrderApi;

abstract class PaymentFactory
{
    /**
     * @param Api\OrderApi $orderApi
     * @param Api\AgreementApi $agreementApi
     * 
     * @return \Payum\Payment
     */
    public static function create(OrderApi $orderApi, AgreementApi $agreementApi = null)
    {
        $payment = new Payment;
        
        if ($agreementApi) {
            $payment->addApi($agreementApi);
            
            $payment->addAction(new AgreementDetailsStatusAction);
            
            $payment->addAction(new CreateAgreementAction);
            $payment->addAction(new DeleteAgreementAction);
            $payment->addAction(new CheckAgreementAction);
            $payment->addAction(new AutoPayAgreementAction);
        }

        $payment->addApi($orderApi);

        $payment->addExtension(new EndlessCycleDetectorExtension);

        $payment->addAction(new InitializeOrderAction);
        $payment->addAction(new CompleteOrderAction);
        
        $payment->addAction(new PaymentDetailsCaptureAction);
        $payment->addAction(new PaymentDetailsStatusAction);
        $payment->addAction(new AutoPayCaptureAction);
        $payment->addAction(new PaymentDetailsCaptureAction);

        return $payment;
    }

    /**
     */
    private  function __construct()
    {
    }
}