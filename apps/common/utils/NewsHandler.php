<?php

namespace NewsServer\Common\Utils;

use PicoFeed\Reader\Reader;
use PHPHtmlParser\Dom;
use NewsServer\Common\Collections\NewsSource;
use NewsServer\Common\Collections\NewsItem;

class NewsHandler
{
    protected $rss;

    protected $html;

    protected $sources = [];

    public function __construct()
    {
        $this->rss = new Reader();
        $this->html = new Dom();
    }

    public function discover($url)
    {
        try {
            $resource = $this->rss->discover($url);

            $parser = $this->rss->getParser(
                $resource->getUrl(),
                $resource->getContent(),
                $resource->getEncoding()
            );

            $feed = $parser->execute();
            return ['resource' => $resource, 'feed' => $feed];
        } catch (\Exception $e) {
            throw new \Phalcon\Exception($e->getMessage(), $e->getCode());
        }
    }

    public function fetch($source)
    {
        try {
            $resource = $this->rss->download($source->getFeedUrl(), $source->getLastModified(), $source->getEtag());
            $sourceItems = [];

            if ($resource->isModified()) {
                $parser = $this->rss->getParser(
                    $resource->getUrl(),
                    $resource->getContent(),
                    $resource->getEncoding()
                );

                $feed = $parser->execute();
                $items = $feed->getItems();

                foreach ($items as $item) {
                    $newsItem = new NewsItem();
                    $newsItem->setId(new \MongoId());
                    $newsItem->setSourceId($source->getId());
                    $newsItem->setTitle($item->getTitle());
                    $newsItem->setAuthor($item->getAuthor());
                    $newsItem->setContent($item->getContent());
                    $newsItem->setUrl($item->getUrl());
                    $newsItem->setTimestamp($item->getDate()->getTimestamp());

                    $this->html->loadStr($item->getContent(), []);

                    $imgTags = $this->html->find('img');
                    foreach ($imgTags as $img) {
                        $newsItem->addImage($img->getTag()->getAttribute('src')['value']);
                    }

                    $vidsTags = $this->html->find('iframe');
                    foreach ($vidsTags as $iframe) {
                        $newsItem->addVideo($iframe->getTag()->getAttribute('src')['value']);
                    }

                    $sourceItems[$item->getId()] = $newsItem->toArray();
                }

                $source->setEtag($resource->getEtag());
                $source->setLastModified($resource->getLastModified());
                $source->save();
            }

            return $sourceItems;
        } catch (\Exception $e) {
            throw new \Phalcon\Exception($e->getMessage(), $e->getCode());
        }
    }

    public function review($item)
    {
        $updatedItems = [];
        $content = utf8_encode($item->getContent());

        //Replace string by config
        $strings = $this->getStrings($item);
        foreach ($strings['content'] as $string) {
            $content = str_replace($string, '', $content);
        }

        //Remove all html tags left
        $content = utf8_decode(strip_tags(str_replace(PHP_EOL . PHP_EOL, '', $content), '<b><s><i><p><ol><ul><li><br>'));
        $intro = substr(strip_tags($content), 0, 146) . '...';

        //Replace string by config
        $images = $item->getImages();
        $imagesArray = [];
        if (count($strings['images'])) {
            foreach ($images as $image) {
                foreach ($strings['images'] as $string) {
                    $image = str_replace($string, '', $image);
                    $imagesArray[] = $image;
                }
            }
        } else {
            $imagesArray = $images;
        }

        $updatedItem = [
            'q'    => ['_id' => $item->getId()],
            'u'    => [
                '$set' => [
                    'intro'      => htmlspecialchars($intro),
                    'content'    => htmlspecialchars($content),
                    'images'     => $imagesArray,
                    'edited'     => true
                ]
            ]
        ];

        return $updatedItem;
    }

    protected function getStrings($item)
    {
        $strings = [];
        $sourceId = (string) $item->getSourceId();
        if (array_key_exists($sourceId, $this->sources)) {
            $strings = $this->sources[$sourceId];
        } else {
            $source = NewsSource::findFirst([
                ['_id' => $item->getSourceId()]
            ]);

            if ($source) {
                $strings = $source->getReplaceStrings();
                $this->sources[$sourceId] = $strings;
            }
        }

        return $strings;
    }
}
