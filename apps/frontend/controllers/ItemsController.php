<?php

namespace NewsServer\Frontend\Controllers;

use NewsServer\Common\Collections\NewsSource;
use NewsServer\Common\Collections\NewsItem;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use NewsServer\Frontend\Forms\ItemsForm;

class ItemsController extends ControllerBase
{
    public function indexAction($currentPage = 1)
    {
        $currentPage = $currentPage >= 1 ? $currentPage : 1;

        $sources = NewsSource::find();
        $sourcesArray = [];
        foreach ($sources as $source) {
            $id = $source->getId();
            $sourcesArray["$id"] = $source->getName();
        }

        $items = NewsItem::find([
            'sort' => [
                'edited'    => 1,
                'approved'  => 1,
                'timestamp' => -1
            ]
        ]);

        $paginator = new PaginatorArray(
            [
                "data"  => $items,
                "limit" => 10,
                "page"  => $currentPage
            ]
        );

        $page = $paginator->getPaginate();
        $items = $page->items;

        $this->view->sources = $sourcesArray;
        $this->view->page = $page;
        $this->view->items = $items;
    }

    public function editAction($itemId)
    {
        try {
            $id = new \MongoId($itemId);
            $item = NewsItem::findFirst([
                ['_id' => $id]
            ]);

            if ($item) {
                $form = new ItemsForm($item);
                if ($this->request->isPost()) {
                    $form->bind($_POST, $item);
                    // Check if the form is valid
                    if ($form->isValid()) {
                        $item->setEdited(true);
                        // Save the entity
                        $item->save();
                        $this->flash->success('Edited successfully.');
                    } else {
                        $this->flash->error('Please validate the entered data and try again.');
                    }
                    $this->view->itemForm = $form;
                } else {
                    $this->view->itemForm = $form;
                }
                $this->view->item = $item;
            } else {
                $this->flash->error('Invalid item id.');
                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'items',
                        'action'     => 'index'
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }

    public function deleteAction($itemId)
    {
        try {
            $id = new \MongoId($itemId);
            $item = NewsItem::findFirst([
                ['_id' => $id]
            ]);

            if ($item) {
                $item->delete();
                $this->flash->success('Deleted successfully.');
            } else {
                $this->flash->error('Invalid item id.');
            }

            $this->dispatcher->forward(
                [
                    'module'     => 'web',
                    'controller' => 'items',
                    'action'     => 'index'
                ]
            );
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }

    public function removeImgAction($itemId, $imgHash)
    {
        $id = new \MongoId($itemId);
        $item = NewsItem::findFirst([
            ['_id' => $id]
        ]);

        if ($item) {
            $item->deleteImage($imgHash);
            $item->save();
            $this->flash->success('Image deleted successfully.');
        } else {
            $this->flash->error('Invalid item id.');
        }

        $this->dispatcher->forward(
            [
                'module'     => 'web',
                'controller' => 'items',
                'action'     => 'edit',
                'param'      => $itemId
            ]
        );
    }
}
