<?php

namespace NewsServer\Common\Collections;

use Phalcon\Mvc\Collection;

class ServerTask extends Collection
{
    public $_id;

    public $taskId;

    public $name;

    public $status;

    public $progress = 0;

    public $output = '';

    public $errors = 0;

    public $startTime;

    public $endTime;

    public $timeSpent;

    const RUNNING = 'running';

    const FAILED = 'failed';

    const FINISHED = 'finished';

    const SUCCESS = 'success';

    public function getSource()
    {
        return "tasks";
    }

    /**
     * Gets the value of _id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the value of _id.
     *
     * @param mixed $_id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Gets the value of taskId.
     *
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Sets the value of taskId.
     *
     * @param mixed $taskId the task id
     *
     * @return self
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of status.
     *
     * @param mixed $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the value of progress.
     *
     * @return mixed
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Sets the value of progress.
     *
     * @param mixed $progress the progress
     *
     * @return self
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Gets the value of output.
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Gets the value of output.
     *
     * @return mixed
     */
    public function getHtmlOutput()
    {
        return str_replace(PHP_EOL, '<br/>', $this->output);
    }

    /**
     * Sets the value of output.
     *
     * @param mixed $output the output
     *
     * @return self
     */
    public function setOutput($output)
    {
        $this->output = utf8_encode($output);

        return $this;
    }

    /**
     * Gets the value of errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the value of errors.
     *
     * @param mixed $errors the errors
     *
     * @return self
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Gets the value of startTime.
     *
     * @return mixed
     */
    public function getStartDate()
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($this->startTime);

        return $datetime;
    }

    /**
     * Gets the value of startTime.
     *
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Sets the value of startTime.
     *
     * @param mixed $startTime the start time
     *
     * @return self
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Gets the value of endTime.
     *
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Sets the value of endTime.
     *
     * @param mixed $endTime the end time
     *
     * @return self
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Gets the value of timeSpent.
     *
     * @return mixed
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Sets the value of timeSpent.
     *
     * @param mixed $timeSpent the time spent
     *
     * @return self
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }
}
