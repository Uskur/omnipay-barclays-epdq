<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

class DirectQueryRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://payments.epdq.co.uk/ncol/prod/querydirect.asp';
    protected $testEndpoint = 'https://mdepayments.epdq.co.uk/ncol/test/querydirect.asp';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', mb_substr($value, 0, 30));
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getData()
    {
        $data = [];

        if ($this->getTransactionReference()) {
            $data['PAYID'] = $this->getTransactionReference();
        } elseif ($this->getTransactionId()) {
            $data['ORDERID'] = $this->getTransactionId();
        } else {
            throw new InvalidRequestException('Preferrably transaction reference or at least transaction ID must be set.');
        }

        $data['PSPID'] = $this->getClientId();
        $data['PSWD'] = $this->getPassword();
        $data['USERID'] = $this->getUserid();

        $data['SHASIGN'] = $this->calculateSha($data, $this->getShaIn());

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->sendRequest(
            $this->getData()
        );

        $data = (array)simplexml_load_string($response->getBody()->getContents());

        if (!empty($data['@attributes'])) {
            $data = $data['@attributes'];
        } else {
            $data = [];
        }

        return $this->response = new DirectQueryResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function calculateSha($data, $shaKey)
    {
        uksort($data, "strnatcmp");

        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= sprintf('%s=%s%s', strtoupper($key), $value, $shaKey);
        }

        return strtoupper(sha1($shaString));
    }

    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    public function getPassword()
    {
        return $this->getParameter('PSWD');
    }

    public function setPassword($value)
    {
        return $this->setParameter('PSWD', $value);
    }

    public function getUserid()
    {
        return $this->getParameter('USERID');
    }

    public function setUserid($value)
    {
        return $this->setParameter('USERID', $value);
    }

    protected function sendRequest($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        return $this->httpClient->request('POST', $this->getEndpoint(),
            $headers,
            http_build_query($data)
        );
    }

}
