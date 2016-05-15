<?php

namespace NewsServer\Frontend\Controllers;

use NewsServer\Common\Collections\ServerTask;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class TasksController extends ControllerBase
{
    public function indexAction($currentPage = 1)
    {
        $currentPage = $currentPage >= 1 ? $currentPage : 1;

        $tasks = ServerTask::find([
            [
                'status'    => [
                    '$ne'  => ServerTask::RUNNING
                ]
            ],
            'sort'      => [
                'endTime'   => -1
            ]
        ]);

        $paginator = new PaginatorArray(
            [
                "data"  => $tasks,
                "limit" => 5,
                "page"  => $currentPage
            ]
        );

        $page = $paginator->getPaginate();
        $tasks = $page->items;

        $this->view->page = $page;
        $this->view->tasks = $tasks;
    }

    public function startAction()
    {
        if ($this->request->isPost()) {
            try {
                $taskName = $this->request->getPost('task');
                $taskRunning = ServerTask::findFirst([
                    [
                        'name'      => $taskName,
                        'status'    => ServerTask::RUNNING
                    ]
                ]);

                if (!$taskRunning) {
                    $command = 'php ' . __DIR__ . '/../../crawler/cli.php ';
                    switch ($taskName) {
                        case 'Fetch':
                            $command .= 'main fetch ' . uniqid('web.', true) . ' ';
                            break;

                        case 'Review':
                            $command .= 'editor review ' . uniqid('web.', true) . ' ';
                            break;

                        case 'Approve':
                            $command .= 'editor approve ' . uniqid('web.', true) . ' ';
                            break;

                        default:
                            throw new \Phalcon\Exception("Undefined Task", 2001);
                            break;
                    }
                    exec($command . '> /dev/null 2>&1 &');
                    $this->flash->success($taskName . ' executed successfully.');
                } else {
                    $this->flash->error($taskName . ' is already running.');
                }
            } catch (\Exception $e) {
                $this->flash->error('Something went wrong.');
            }
        }

        $this->dispatcher->forward(
            [
                'module'     => 'web',
                'controller' => 'tasks',
                'action'     => 'index'
            ]
        );
    }

    public function runningAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $tasks = ServerTask::find([
                [
                    'status'    => ServerTask::RUNNING
                ],
                'sort'      => [
                    'startTime'   => 1
                ]
            ]);

            $runningArray = [];
            foreach ($tasks as $task) {
                $runningArray[] = [
                    'id'        => $task->getId()->__toString(),
                    'taskId'    => $task->getTaskId(),
                    'name'      => $task->getName(),
                    'status'    => $task->getStatus(),
                    'progress'  => $task->getProgress(),
                    'errors'    => $task->getErrors(),
                    'output'    => $task->getHtmlOutput(),
                    'startDate' => $task->getStartDate()->format('d/m/Y H:i')
                ];
            }

            $return = new \StdClass();
            $return->RunningTasks = $runningArray;
            echo json_encode($return);
        } else {
            $this->response->redirect('/web/tasks');
        }
    }

    public function finishedAction($currentPage = 1)
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $currentPage = $currentPage >= 1 ? $currentPage : 1;

            $tasks = ServerTask::find([
                [
                    'status'    => [
                        '$ne'  => ServerTask::RUNNING
                    ]
                ],
                'sort'      => [
                    'endTime'   => -1
                ]
            ]);

            $paginator = new PaginatorArray(
                [
                    "data"  => $tasks,
                    "limit" => 5,
                    "page"  => $currentPage
                ]
            );

            $page = $paginator->getPaginate();
            $tasks = $page->items;

            $finishedArray = [];
            foreach ($tasks as $task) {
                $finishedArray[] = [
                    'id'        => $task->getId()->__toString(),
                    'taskId'    => $task->getTaskId(),
                    'name'      => $task->getName(),
                    'status'    => $task->getStatus(),
                    'errors'    => $task->getErrors(),
                    'output'    => $task->getHtmlOutput(),
                    'startDate' => $task->getStartDate()->format('d/m/Y H:i'),
                    'timeSpent' => $task->getTimeSpent()
                ];
            }

            $return = new \StdClass();
            $return->FinishedTasks = $finishedArray;
            echo json_encode($return);
        } else {
            $this->response->redirect('/web/tasks');
        }
    }
}
