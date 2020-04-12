<?php

namespace PayEx\Api\Service\Invoice\Transaction\Request;

use PayEx\Api\Service\Request;

class CreateCancellation extends Request
{
    public function setup()
    {
        $this->setRequestMethod('POST');
        $this->setRequestEndpoint('/psp/invoice/payments/%s/cancellations');
    }
}
