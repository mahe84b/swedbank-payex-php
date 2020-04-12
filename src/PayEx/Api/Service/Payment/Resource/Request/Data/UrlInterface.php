<?php

namespace PayEx\Api\Service\Payment\Resource\Request\Data;

use PayEx\Api\Service\Data\ResourceInterface;

/**
 * Payment order url interface
 *
 * @api
 */
interface UrlInterface extends ResourceInterface
{
    const COMPLETE_URL = 'complete_url';
    const CANCEL_URL = 'cancel_url';
    const CALLBACK_URL = 'callback_url';
    const TERMS_OF_SERVICE_URL = 'terms_of_service_url';
    const LOGO_URL = 'logo_url';

    /**
     * @return string
     */
    public function getCompleteUrl();

    /**
     * @param string $completeUrl
     * @return $this
     */
    public function setCompleteUrl($completeUrl);

    /**
     * @return string
     */
    public function getCancelUrl();

    /**
     * @param string $cancelUrl
     * @return $this
     */
    public function setCancelUrl($cancelUrl);

    /**
     * @return string
     */
    public function getCallbackUrl();

    /**
     * @param string $callbackUrl
     * @return $this
     */
    public function setCallbackUrl($callbackUrl);

    /**
     * @return string
     */
    public function getTermsOfService();

    /**
     * @param string $termsOfServiceUrl
     * @return $this
     */
    public function setTermsOfService($termsOfServiceUrl);

    /**
     * @return string
     */
    public function getLogoUrl();

    /**
     * @param string $logoUrl
     * @return $this
     */
    public function setLogoUrl($logoUrl);
}
