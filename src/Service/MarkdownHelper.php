<?php
/**
 * Created by PhpStorm.
 * User: murgic
 * Date: 15.02.19
 * Time: 22:41
 */

namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MarkdownHelper constructor.
     */
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $logger)
    {
        $this->markdown = $markdown;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function parse(string $source): string
    {
        if(stripos($source, 'bacon') != false) {
            $this->logger->info('They are talking about bacon again!');
        }

        $item = $this->cache->getItem('markdown_' . md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}