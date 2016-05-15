<?php

namespace NewsServer\Frontend\Controllers;

use NewsServer\Common\Collections\NewsSource;
use NewsServer\Frontend\Forms\SourcesForm;

class SourcesController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->sources = NewsSource::find([
            ['active' => true]
        ]);
    }

    public function addAction()
    {
        $form = new SourcesForm(null, ['mode' => 'add']);

        if ($this->request->isPost()) {
            $urls = $this->request->getPost('url');
            $pattern = '/^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)((\&|\?)(.*?)\=(.*?))?$/';
            foreach ($urls as $url) {
                if (preg_match($pattern, $url)) {
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

                            $this->flash->success('Source ' . $url . ' added successfully.');
                        } else {
                            $this->flash->error('Source ' . $url . ' already exists.');
                        }
                    } catch (\Exception $e) {
                        $this->flash->error('Source ' . $url . ' couldn\'t be added: ' . $e->getMessage());
                        continue;
                    }
                } else {
                    $this->flash->error('Source ' . $url . ' couldn\'t be added.');
                }
            }
        }

        $this->view->addForm = $form;
    }

    protected function alreadyExists($url)
    {
        return NewsSource::findFirst([
            [
                'url' => $url
            ]
        ]);
    }

    public function editAction($sourceId)
    {
        try {
            $id = new \MongoId($sourceId);
            $source = NewsSource::findFirst([
                ['_id' => $id]
            ]);

            if ($source) {
                $form = new SourcesForm($source);
                if ($this->request->isPost()) {
                    $form->bind($_POST, $source);
                    // Check if the form is valid
                    if ($form->isValid()) {
                        // Save the entity
                        $strings = [];
                        $stringTypes = $source->getReplaceStrings();
                        foreach ($stringTypes as $type => $string) {
                            $key = 'replaceStrings_' . $type;
                            $strings[$type] = explode(', ', $this->request->getPost($key));
                            unset($source->$key);
                        }
                        $source->setReplaceStrings($strings);
                        $source->save();
                        $this->flash->success('Edited successfully.');
                    } else {
                        $this->flash->error('Please validate the entered data and try again.');
                    }
                    $this->view->sourceForm = $form;
                } else {
                    $this->view->sourceForm = $form;
                }
                $this->view->source = $source;
            } else {
                $this->flash->error('Invalid source id.');
                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'sources',
                        'action'     => 'index'
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }

    public function enableAction($sourceId)
    {
        try {
            $id = new \MongoId($sourceId);
            $source = NewsSource::findFirst([
                ['_id' => $id]
            ]);

            if ($source) {
                $source->setActive(true);
                $source->save();
                $this->flash->success('Enabled successfully.');

                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'sources',
                        'action'     => 'index'
                    ]
                );
            } else {
                $this->flash->error('Invalid source id.');
                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'sources',
                        'action'     => 'disabled'
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }

    public function disableAction($sourceId)
    {
        try {
            $id = new \MongoId($sourceId);
            $source = NewsSource::findFirst([
                ['_id' => $id]
            ]);

            if ($source) {
                $source->setActive(false);
                $source->save();
                $this->flash->success('Disabled successfully.');

                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'sources',
                        'action'     => 'disabled'
                    ]
                );
            } else {
                $this->flash->error('Invalid source id.');
                $this->dispatcher->forward(
                    [
                        'module'     => 'web',
                        'controller' => 'sources',
                        'action'     => 'index'
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }

    public function disabledAction()
    {
        $this->view->sources = NewsSource::find([
            ['active' => false]
        ]);
    }

    public function resetAction($sourceId)
    {
        try {
            $id = new \MongoId($sourceId);
            $source = NewsSource::findFirst([
                ['_id' => $id]
            ]);

            if ($source) {
                $source->setLastModified('');
                $source->setEtag('');
                $source->save();
                $this->flash->success('Reset successfully.');
            } else {
                $this->flash->error('Invalid source id.');
            }

            $this->dispatcher->forward(
                [
                    'module'     => 'web',
                    'controller' => 'sources',
                    'action'     => 'index'
                ]
            );
        } catch (\Exception $e) {
            $this->flash->error('Something went wrong.');
        }
    }
}
