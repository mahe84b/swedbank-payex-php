<?php

use PayEx\Api\Client\Client;
use PayEx\Api\Service\Paymentorder\Resource\PaymentorderObject;
use PayEx\Api\Service\Paymentorder\Resource\PaymentorderUrl as PaymentorderUrlData;
use PayEx\Api\Service\Paymentorder\Resource\PaymentorderPayeeInfo;
use PayEx\Api\Service\Paymentorder\Resource\Request\Paymentorder;
use PayEx\Api\Service\Paymentorder\Request\Purchase;
use PayEx\Api\Service\Paymentorder\Request\GetCurrentPayment;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Client $client */
    protected $client;

    protected function setUp(): void
    {
        if (!defined('MERCHANT_TOKEN') ||
            MERCHANT_TOKEN === '<merchant_token>') {
            $this->fail('MERCHANT_TOKEN not configured in INI file or environment variable.');
        }

        if (!defined('PAYEE_ID') ||
            PAYEE_ID === '<payee_id>') {
            $this->fail('PAYEE_ID not configured in INI file or environment variable.');
        }

        $this->client = new Client();
        $this->client->setMerchantToken(MERCHANT_TOKEN)
            ->setPayeeId(PAYEE_ID)
            ->setMode(Client::MODE_TEST);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    /**
     * @param $length
     * @return bool|string
     */
    protected function generateRandomString($length = 12)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @return string|null
     * @throws \PayEx\Api\Client\Exception
     */
    protected function getPaymentOrderId()
    {
        return $this->createPaymentOrder()->getResponseData()['payment_order']['id'];
    }

    /**
     * @return mixed
     * @throws \PayEx\Api\Client\Exception
     */
    protected function getPaymentToken()
    {
        $currentPayment = new GetCurrentPayment();
        $currentPayment->setClient($this->client)
            ->setRequestEndpoint( $this->getPaymentOrderId() . '/currentPayment');

        $response = $currentPayment->send();

        return $response['payment']['payment_token'];
    }

    /**
     * @return \PayEx\Api\Service\Data\ResponseInterface
     * @throws \PayEx\Api\Client\Exception
     */
    private function createPaymentOrder()
    {
        $urlData = new PaymentorderUrlData();
        $urlData->setHostUrls(['https://example.com', 'https://example.net'])
            ->setCompleteUrl('https://example.com/payment-completed')
            ->setCancelUrl('https://example.com/payment-canceled')
            ->setCallbackUrl('https://api.internaltest.payex.com/psp/fakecallback')
            ->setTermsOfService('https://example.com/termsandconditoons.pdf')
            ->setLogoUrl('https://example.com/logo.png');

        $payeeInfo = new PaymentorderPayeeInfo();
        $payeeInfo->setPayeeId(PAYEE_ID)
            ->setPayeeReference($this->generateRandomString())
            ->setPayeeName('Merchant1')
            ->setProductCategory('A123')
            ->setOrderReference('or-123456');

        $paymentOrder = new Paymentorder();
        $paymentOrder->setOperation('Purchase')
            ->setCurrency('NOK')
            ->setAmount('1500')
            ->setVatAmount(0)
            ->setDescription('Test Purchase')
            ->setUserAgent('Mozilla/5.0...')
            ->setLanguage('nb-NO')
            ->setGeneratePaymentToken(true)
            ->setDisablePaymentMenu(false)
            ->setUrls($urlData)
            ->setPayeeInfo($payeeInfo);

        $paymentOrderObject = new PaymentorderObject();
        $paymentOrderObject->setPaymentorder($paymentOrder);

        $purchaseRequest = new Purchase($paymentOrderObject);
        $purchaseRequest->setClient($this->client);

        $response = $purchaseRequest->send();
        return $response;
    }
}
