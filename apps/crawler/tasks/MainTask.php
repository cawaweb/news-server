<?php

use NewsServer\Common\Collections\NewsSource;
use NewsServer\Common\Collections\NewsItem;
use NewsServer\Common\Utils\ServerTask;

class MainTask extends \Phalcon\CLI\Task
{
    public function fetchAction($args = null)
    {
        $taskId = null;
        if ($args != null) {
            $taskId = $args[0];
        }
        $this->task->start('Fetch', $taskId);

        $sources = $exists = NewsSource::find([
            [
                'active' => true
            ]
        ]);

        if ($sources) {
            $allItems = [];
            //TODO improove progress calculation
            $progress = 90 / count($sources);

            foreach ($sources as $source) {
                try {
                    $items = $this->news->fetch($source);
                    $this->task->addOutput('Processed ' . count($items) . ' items from ' . $source->getName());
                    $allItems = array_merge($allItems, $items);
                } catch (\Exception $e) {
                    $this->task->notifyError('Error retrieving feed items from the source: ' . $source->getName());
                    continue;
                }
                $this->task->notifyProgress($progress);
            }

            $itemsCollection = $this->mongo->items;
            try {
                if (count($allItems)) {
                    $itemsCollection->batchInsert($allItems, ['continueOnError' => true]);
                    $this->task->addOutput('News Updated!');
                } else {
                    $this->task->addOutput('Nothing to update.');
                }
                $this->task->notifyProgress(10);
            } catch (\Exception $e) {
                $this->task->terminate($e->getMessage());
                throw new \Phalcon\Exception($e->getMessage(), $e->getCode());
            }
        } else {
            $this->task->addOutput('Nothing to update.');
        }
    }
}
