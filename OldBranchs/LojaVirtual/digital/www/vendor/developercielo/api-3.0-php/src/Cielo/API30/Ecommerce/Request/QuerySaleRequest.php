<?php

namespace Cielo\API30\Ecommerce\Request;

use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Environment;
use Cielo\API30\Merchant;

/**
 * Class QuerySaleRequest
 *
 * @package Cielo\API30\Ecommerce\Request
 */
class QuerySaleRequest extends AbstractRequest
{

    private $environment;

    /**
     * QuerySaleRequest constructor.
     *
     * @param Merchant    $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    /**
     * @param $paymentId
     *
     * @return null
     * @throws \Cielo\API30\Ecommerce\Request\CieloRequestException
     * @throws \RuntimeException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getApiQueryURL() . '1/sales/' . $paymentId;

        return $this->sendRequest('GET', $url);
    }

    /**
     * @param $json
     *
     * @return Sale
     */
    protected function unserialize($json)
    {
        return Sale::fromJson($json);
    }
}
