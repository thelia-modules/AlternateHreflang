<?php

namespace AlternateHreflang\Listener;

use AlternateHreflang\Event\AlternateHreflangEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Model\ConfigQuery;
use Thelia\Tools\URL;

class AlternateHreflangListener implements EventSubscriberInterface
{
    /**
     * @return array
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            AlternateHreflangEvent::BASE_EVENT_NAME => [
                'generateAlternateHreflang', 128
            ]
        ];
    }

    public function generateAlternateHreflang(AlternateHreflangEvent $event)
    {
        $view = $event->getRequest()->attributes->get('_view');

        $multiDomainActivated = ConfigQuery::isMultiDomainActivated();

        switch ($view) {
            case 'product':
                $uri = $this->findUrlFromView($event, 'product', 'product_id');
                break;
            case 'folder':
                $uri = $this->findUrlFromView($event, 'folder', 'folder_id');
                break;
            case 'content':
                $uri = $this->findUrlFromView($event, 'content', 'content_id');
                break;
            case 'category':
                $uri = $this->findUrlFromView($event, 'category', 'category_id');
                break;
            default:
                if (!$multiDomainActivated) {
                    $uri = $event->getRequest()->getRequestUri();

                    if (preg_match('/lang=[a-zA-Z_]{5}/', $uri)) {
                        $uri = preg_replace('/lang=[a-zA-Z_]{5}/', 'lang=' . $event->getLang()->getLocale(), $uri);
                    } elseif (\strpos($uri, '?')) {
                        $uri .= '&lang=' . $event->getLang()->getLocale();
                    } else {
                        $uri .= '?lang=' . $event->getLang()->getLocale();
                    }
                } else {
                    $uri = $event->getRequest()->getRequestUri();
                }
                break;
        }

        if ($multiDomainActivated) {
            $baseUrl = $event->getLang()->getUrl();

            $uri = trim($uri, '/');

            // remove lang for home page
            if (preg_match('/^lang=[a-zA-Z_]{5}$/', $uri)) {
                $uri = '';
            }
        } else {
            $baseUrl = ConfigQuery::getConfiguredShopUrl();
            if(empty($baseUrl)) {
                $baseUrl = $event->getRequest()->getSchemeAndHttpHost();
            }

            $uri = trim($uri, '/');
        }

        $url = trim($baseUrl, '/') . '/' . $uri;

        $event->setUrl($url);
    }

    protected function findUrlFromView(AlternateHreflangEvent $event, $view, $requestAttributeKey)
    {
        $id = $event->getRequest()->attributes->get($requestAttributeKey);

        if (!empty($id)) {
            if (null !== $rewritingRetriever = URL::getInstance()->retrieve($view, $id, $event->getLang()->getLocale())) {
                $url =  !empty($rewritingRetriever->rewrittenUrl) ? $rewritingRetriever->rewrittenUrl : $rewritingRetriever->url;

                return $this->generateUriFromUrl($url);
            }
        }

        return null;
    }

    protected function generateUriFromUrl($url)
    {
        $url = parse_url($url);

        if (!empty($url['path'])) {
            return $url['path'] . (!empty($url['query']) ? '?' . $url['query'] : '');
        }

        return null;
    }
}
