<?php

namespace NewsCrawler\Utils;

use NewsServer\Common\Collections\ServerTask;

class TaskHandler
{
    protected $task;

    public function __construct()
    {
        $this->task = new ServerTask();
    }

    /**
     * Starts the task.
     *
     * @param string $name
     * @param string $taskId
     *
     */
    public function start($name, $taskId = null)
    {
        if (!$this->isRunning()) {
            $this->task->setStartTime(time());
            $this->task->setName($name);
            if ($taskId != null) {
                $this->task->setTaskId($taskId);
            } else {
                $this->task->setTaskId(uniqid('task.', true));
            }
            $this->task->setStatus(ServerTask::RUNNING);
            $this->task->save();
        } else {
            $this->throwException();
        }
    }

    /**
     * Stops the task.
     *
     */
    public function stop()
    {
        if ($this->isRunning()) {
            if (!$this->isCompleted()) {
                $this->task->setProgress(100);
            }
            if ($this->task->getErrors() > 0) {
                $this->task->setStatus(ServerTask::FINISHED);
            } else {
                $this->task->setStatus(ServerTask::SUCCESS);
            }
            $this->task->setEndTime(time());
            $this->calculateTimeSpent();
            $this->task->save();
        }
    }

    /**
     * Terminates the task.
     *
     * @param string $errorMessage
     *
     */
    public function terminate($errorMessage)
    {
        if ($this->isRunning()) {
            $this->notifyError($errorMessage);
            $this->task->setStatus(ServerTask::FAILED);
            $this->task->setEndTime(time());
            $this->calculateTimeSpent();
            $this->task->save();
        } else {
            $this->throwException();
        }
    }

    /**
     * Notifies an error.
     *
     * @param string $errorMessage
     *
     */
    public function notifyError($errorMessage = null)
    {
        if ($this->isRunning()) {
            $this->task->setErrors($this->task->getErrors() + 1);
            $this->addOutput($errorMessage);
        } else {
            $this->throwException();
        }
    }

    /**
     * Adds output to the task.
     *
     * @param string $message
     *
     */
    public function addOutPut($message)
    {
        if ($this->isRunning()) {
            $message .= PHP_EOL;
            $this->task->setOutput($this->task->getOutput() . $message);
        } else {
            $this->throwException();
        }
    }

    /**
     * Notifies progress.
     *
     * @param float $progress
     *
     */
    public function notifyProgress($progress)
    {
        if (!$this->isCompleted()) {
            $this->task->setProgress($this->task->getProgress() + $progress);
        } else {
            $this->task->setProgress(100);
        }
        $this->task->save();
    }

    /**
     * Checks if the task is running.
     *
     * @return boolean
     *
     */
    protected function isRunning()
    {
        return $this->task->getStatus() == ServerTask::RUNNING;
    }

    /**
     * Checks if the task is completed.
     *
     * @return boolean
     *
     */
    protected function isCompleted()
    {
        return $this->task->getProgress() >= 100;
    }

    /**
     * Calculates the time spent.
     *
     */
    protected function calculateTimeSpent()
    {
        $totalTime = $this->task->getEndTime() - $this->task->getStartTime();
        $this->task->setTimeSpent($totalTime);
    }

    /**
     * Throws an exception.
     *
     */
    protected function throwException()
    {
        if ($this->isRunning()) {
            throw new \Phalcon\Exception("Task is already running", 1001);
        } else {
            if ($this->task->getStatus() != null) {
                throw new \Phalcon\Exception("Task has already been stopped.", 1002);
            } else {
                throw new \Phalcon\Exception("Task has not been initialized.", 1003);
            }
        }
    }
}
