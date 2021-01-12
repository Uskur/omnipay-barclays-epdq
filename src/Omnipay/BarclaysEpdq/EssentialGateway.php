<?php

namespace Omnipay\BarclaysEpdq;

use Omnipay\Common\AbstractGateway;

/**
 * BarclaysEpdq Essential Gateway
 *
 * @link http://www.barclaycard.co.uk/business/epdq-cpi/technical-info
 */
class EssentialGateway extends AbstractGateway
{

    public function getName()
    {
        return 'BarclaysEpdq';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'testMode' => false,
            'language' => 'en_US',
            'callbackMethod' => 'POST',
            'shaIn' => '',
            'shaOut' => '',
            'userid' => '',
            'password' => ''
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BarclaysEpdq\Message\EssentialPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\EssentialPurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BarclaysEpdq\Message\EssentialCompletePurchaseRequest
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\EssentialCompletePurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\EssentialRefundRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function status(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\DirectQueryRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function extendedStatus(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\DirectQueryRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }

    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
    }

    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    public function setShaIn($value)
    {
        return $this->setParameter('shaIn', $value);
    }

    public function getShaOut()
    {
        return $this->getParameter('shaOut');
    }

    public function setShaOut($value)
    {
        return $this->setParameter('shaOut', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('declineUrl');
    }

    public function getExceptionUrl()
    {
        return $this->getParameter('exceptionUrl');
    }

    public function setReturnUrl($value)
    {
        $this->setParameter('returnUrl', $value);
        $this->setParameter('declineUrl', $value);
        $this->setParameter('exceptionUrl', $value);

        return $this;
    }

    public function getLanguage()
    {
        if (empty($this->getParameter('language'))) {
            return 'en_US';
        }
        $language = $this->getParameter('language');
        $allowedLanguages = [
            'ar_AR', 'cs_CZ', 'dk_DK', 'de_DE', 'el_GR', 'en_US', 'es_ES', 'fi_FI', 'fr_FR', 'he_IL', 'hu_HU',
            'it_IT', 'ja_JP', 'ko_KR', 'nl_BE', 'nl_NL', 'no_NO', 'pl_PL', 'pt_PT', 'ru_RU', 'se_SE', 'sk_SK',
            'tr_TR', 'zh_CN'
        ];
        if (!in_array($language, $allowedLanguages)) {
            throw new InvalidRequestException('Language must be one of the allowed languages.');
        }
        return $language;
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getUserid()
    {
        return $this->getParameter('userid');
    }

    public function setUserid($value)
    {
        return $this->setParameter('userid', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
}
