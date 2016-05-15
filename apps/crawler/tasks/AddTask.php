<?php

use NewsServer\Common\Collections\NewsSource;

class AddTask extends \Phalcon\CLI\Task
{
    public function sourceAction($args)
    {
        foreach ($args as $arg) {
            $url = $arg;

            try {
                $discover = $this->news->discover($url);

                $resource = $discover['resource'];
                $feed = $discover['feed'];

                if (!$this->alreadyExists($feed->getSiteUrl())) {
                    $source = new NewsSource();
                    $source->setName($feed->getTitle());
                    $source->setDescription($feed->getDescription());
                    $source->setUrl($feed->getSiteUrl());
                    $source->setFeedUrl($feed->getFeedUrl());
                    $source->setEtag($resource->getEtag());
                    $source->setLastModified($resource->getLastModified());

                    $source->save();

                    echo 'Source ' . $feed->getTitle() . ' added successfully.' . PHP_EOL;
                } else {
                    throw new \Phalcon\Exception('Source ' . $feed->getTitle() . ' already exists.', 1000);
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
                continue;
            }
        }
    }

    protected function alreadyExists($url)
    {
        return NewsSource::findFirst([
            [
                'url' => $url
            ]
        ]);
    }
}
