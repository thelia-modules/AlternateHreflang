<?php

namespace AlternateHreflang\Listener;

use AlternateHreflang\Event\AlternateHreflangEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

        switch ($view) {
            case 'product':
                $this->findUrlFromView($event, 'product', 'product_id');
                break;
            case 'folder':
                $this->findUrlFromView($event, 'folder', 'folder_id');
                break;
            case 'content':
                $this->findUrlFromView($event, 'content', 'content_id');
                break;
            case 'category':
                $this->findUrlFromView($event, 'category', 'category_id');
                break;
            case 'index':
                $event->setForceEmptyUri(true);
                break;
            default:
                $event->setUri($event->getRequest()->getUri());
                break;
        }
    }

    protected function findUrlFromView(AlternateHreflangEvent $event, $view, $requestAttributeKey)
    {
        $id = $event->getRequest()->attributes->get($requestAttributeKey);

        if (!empty($id)) {
            if (null !== $rewritingRetriever = URL::getInstance()->retrieve($view, $id, $event->getLang()->getLocale())) {
                $url =  !empty($rewritingRetriever->rewrittenUrl) ? $rewritingRetriever->rewrittenUrl : $rewritingRetriever->url;

                $uri = $this->generateUriFromUrl($url);

                if (null !== $uri) {
                    $event->setUri($uri);
                }
            }
        }
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
