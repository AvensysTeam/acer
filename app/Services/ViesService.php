<?php

namespace App\Services;

use SoapClient;

class ViesService
{
    protected $wsdl = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    public function validateVAT(string $countryCode, string $vatNumber)
    {
        try {
            $client = new SoapClient($this->wsdl);
            $params = [
                'countryCode' => $countryCode,
                'vatNumber'   => $vatNumber,
            ];
            $response = $client->checkVat($params);

            return [
                'valid'       => $response->valid,
                'name'        => $response->name,
                'address'     => $response->address,
                'countryCode' => $response->countryCode,
                'vatNumber'   => $response->vatNumber,
            ];
        } catch (\Exception $e) {
            return [
                'error'   => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
