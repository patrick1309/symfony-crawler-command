<?php 

namespace App\Observer;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver as CrawlObserversCrawlObserver;

class CrawlObserver extends CrawlObserversCrawlObserver {

    private $crawledPages = [];
    private $failedCrawledPages = [];

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $this->crawledPages[] = [
            'url' => urldecode($url),
            'statusCode' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'redirectTo' => $response->getHeaders()['Location'][0] ?? ''
        ];
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        $this->failedCrawledPages[] = urldecode($url);
    }

    public function getCrawlingResults(): array
    {
        return [
            'success' => $this->crawledPages,
            'failed' => $this->failedCrawledPages,
        ];
    }
};