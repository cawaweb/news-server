<?php

namespace NewsServer\Common\Collections;

use Phalcon\Mvc\Collection;

class NewsSource extends Collection
{
    public $_id;

    public $name;

    public $description;

    public $url;

    public $feedUrl;

    public $etag;

    public $lastModified;

    public $active = true;

    public $replaceStrings = ['images' => [], 'content' => []];

    public function getSource()
    {
        return "sources";
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
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param mixed $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the value of feedUrl.
     *
     * @return mixed
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * Sets the value of feedUrl.
     *
     * @param mixed $feedUrl the feed url
     *
     * @return self
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl;

        return $this;
    }

    /**
     * Gets the value of etag.
     *
     * @return mixed
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Sets the value of etag.
     *
     * @param mixed $etag the etag
     *
     * @return self
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;

        return $this;
    }

    /**
     * Gets the value of lastModified.
     *
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Sets the value of lastModified.
     *
     * @param mixed $lastModified the last modification
     *
     * @return self
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Gets the value of active.
     *
     * @return mixed
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Sets the value of active.
     *
     * @param boolean $active the active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Gets the value of processed.
     *
     * @return mixed
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Gets the value of replaceStrings.
     *
     * @return mixed
     */
    public function getReplaceStrings()
    {
        return $this->replaceStrings;
    }

    /**
     * Sets the value of replaceStrings.
     *
     * @param mixed $replaceStrings the replace strings
     *
     * @return self
     */
    public function setReplaceStrings($replaceStrings)
    {
        $this->replaceStrings = $replaceStrings;

        return $this;
    }
}
