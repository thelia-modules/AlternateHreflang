<?php

namespace AlternateHreflang\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;
use Thelia\Model\Lang;

class AlternateHreflangEvent extends Event
{
    const BASE_EVENT_NAME = 'get.alternate.hreflang';

    /** @var Lang */
    protected $lang;

    /** @var Request */
    protected $request;

    /** @var string */
    protected $url;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return AlternateHreflangEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
