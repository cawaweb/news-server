<?php

use NewsServer\Common\Collections\NewsItem;

class EditorTask extends \Phalcon\CLI\Task
{
    public function reviewAction($args = null)
    {
        $taskId = null;
        if ($args != null) {
            $taskId = $args[0];
        }
        $this->task->start('Review', $taskId);

        $items = NewsItem::find([
            ['edited' => false]
        ]);

        if ($items) {
            $collection = $this->mongo->items;
            $updateBatch = new \MongoUpdateBatch($collection);
            $totalItems = count($items);
            //TODO improove progress calculation
            $progress = 50 / $totalItems;
            $this->task->addOutput('Reviewing...');
            try {
                foreach ($items as $item) {
                    $updatedItem = $this->news->review($item);
                    $updateBatch->add($updatedItem);
                    $this->task->notifyProgress($progress);
                }

                if ($totalItems) {
                    $updateBatch->execute();
                    $this->task->addOutput($totalItems . ' items reviewed.');
                    $this->task->notifyProgress(50);
                }
            } catch (\Exception $e) {
                $this->task->terminate($e->getMessage());
                throw new \Phalcon\Exception($e->getMessage(), $e->getCode());
            }
        } else {
            $this->task->addOutput('Nothing to update.');
        }
    }
}
