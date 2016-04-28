<?php

namespace Core\Service;

/**
 * Class CSRFToken
 *
 * @package Core\Service
 * @author sumiyoshi
 */
class CSRFToken
{
    use \Core\Traits\Singleton;


    const SESSION_KEY = 'CSRFToken';

    /**
     * @param int $lifetime
     * @return string
     */
    public function generate($lifetime = 300)
    {
        $token = rtrim(base64_encode(openssl_random_pseudo_bytes(32)), '=');
        $this->setSessionToken($token)->setLifetime(time() + $lifetime);

        return $token;
    }

    /**
     * @param $token
     * @return bool
     */
    public function isValid($token)
    {
        $sessionToken = $this->getSessionToken();
        $lifetime = $this->getLifetime();

        #region トークンの有効期限
        if (time() > $lifetime) {
            return false;
        }
        #endregion

        if (!$sessionToken || !$token) {
            return false;
        }

        #region トークンの妥当性
        if ($sessionToken !== $token) {
            return false;
        }
        #endregion

        $this->clearSessionToken();

        return true;
    }

    /**
     * @return $this
     */
    private function clearSessionToken()
    {
        $session = $this->getSession();
        $session->token = null;

        return $this;
    }

    /**
     * @param $token
     * @return $this
     */
    private function setSessionToken($token)
    {
        $session = $this->getSession();
        $session->token = $token;

        return $this;
    }

    /**
     * @param $lifetime
     * @return $this
     */
    private function setLifetime($lifetime)
    {
        $session = $this->getSession();
        $session->lifetime = $lifetime;

        return $this;
    }

    /**
     * @return string
     */
    private function getSessionToken()
    {
        $session = $this->getSession();
        return $session->token;
    }

    /**
     * @return int
     */
    private function getLifetime()
    {
        $session = $this->getSession();
        return $session->lifetime;
    }

    private function getSession()
    {
        return new \Zend\Session\Container(static::SESSION_KEY);
    }

}