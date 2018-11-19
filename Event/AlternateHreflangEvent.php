<?php

namespace AlternateHreflang\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Model\Lang;

class AlternateHreflangEvent extends Event
{
    const BASE_EVENT_NAME = 'get.alternate.hreflang';

    /** @var Lang */
    protected $lang;

    /** @var Request */
    protected $request;

    /** @var string */
    protected $uri;

    /** @var bool */
    protected $forceEmptyUri = false;

    public function __construct(Lang $lang, Request $request)
    {
        $this->lang = $lang;
        $this->request = $request;
    }

    /**
     * @return Lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return AlternateHreflangEvent
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForceEmptyUri()
    {
        return $this->forceEmptyUri;
    }

    /**
     * @param bool $forceEmptyUri
     * @return AlternateHreflangEvent
     */
    public function setForceEmptyUri($forceEmptyUri)
    {
        $this->forceEmptyUri = $forceEmptyUri;
        return $this;
    }
}
