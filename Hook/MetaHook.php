<?php

namespace AlternateHreflang\Hook;

use AlternateHreflang\AlternateHreflang;
use AlternateHreflang\Event\AlternateHreflangEvent;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Lang;
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

        $currentLocale = $request->getSession()->getLang()->getLocale();

        $langs = LangQuery::create()
            ->filterByVisible(true)
            ->find();

        $metas = [];

        $defaultHrefLang = null;
        $currentHrefLang = null;

        /** @var Lang $lang */
        foreach ($langs as $lang) {
            $event = new AlternateHreflangEvent($lang, $request);

            $this->eventDispatcher->dispatch($event, AlternateHreflangEvent::BASE_EVENT_NAME);

            $hreflangFormat = AlternateHreflang::getConfigValue(AlternateHreflang::CONFIG_KEY_HREFLANG_FORMAT);
            if (0 === (int) $hreflangFormat) {
                $hreflang = strtolower(explode('_', $lang->getLocale())[0]);
            } else {
                $hreflang = strtolower(str_replace('_', '-', $lang->getLocale()));
            }

            if (!empty($event->getUrl())) {
                if ($lang->getByDefault()) {
                    $defaultHrefLang = '<link rel="alternate" hreflang="x-default" href="' . $event->getUrl() . '" />';
                }

                if ($lang->getLocale() === $currentLocale) {
                    $currentHrefLang = '<link rel="alternate" hreflang="' . $hreflang . '" href="' . $event->getUrl() . '" />';
                } else {
                    $metas[] = '<link rel="alternate" hreflang="' . $hreflang . '" href="' . $event->getUrl() . '" />';
                }
            }
        }

        // current language
        if (null !== $currentHrefLang) {
            $hookRender->add($currentHrefLang);
        }

        // other languages
        foreach ($metas as $meta) {
            $hookRender->add($meta);
        }

        // default language
        if (null !== $defaultHrefLang) {
            $hookRender->add($defaultHrefLang);
        }
    }
}
