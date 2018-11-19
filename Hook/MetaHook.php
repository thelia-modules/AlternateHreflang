<?php

namespace AlternateHreflang\Hook;

use AlternateHreflang\Event\AlternateHreflangEvent;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\LangQuery;

class MetaHook extends BaseHook
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EventDispatcherInterface $eventDispatcher)
    {
        $this->requestStack = $requestStack;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param HookRenderEvent $hookRender
     */
    public function onMainHeadBottom(HookRenderEvent $hookRender)
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $currentLang = $request->getSession()->getLang();

        $langs = LangQuery::create()
            ->filterByVisible(true)
            ->filterById($currentLang->getId(), Criteria::NOT_EQUAL)
            ->find();

        foreach ($langs as $lang) {
            $event = new AlternateHreflangEvent($lang, $request);

            $this->eventDispatcher->dispatch(AlternateHreflangEvent::BASE_EVENT_NAME, $event);

            $hreflang = strtolower(str_replace('_', '-', $lang->getLocale()));

            if (!empty($event->getUrl())) {
                $hookRender->add('<link rel="alternate" hreflang="' . $hreflang . '" href="' . $event->getUrl() . '" />');
            }
        }
    }
}
