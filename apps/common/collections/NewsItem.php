<?php

namespace NewsServer\Common\Collections;

use Phalcon\Mvc\Collection;

class NewsItem extends Collection
{
    public $_id;

    public $source_id;

    public $title;

    public $author;

    public $intro;

    public $content;

    public $url;

    public $timestamp;

    public $edited = false;

    public $approved = false;

    public $images = [];

    public $videos = [];

    public $likes = 0;

    public $shares = 0;

    public function getSource()
    {
        return "items";
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
     * Gets the value of source_id.
     *
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * Sets the value of source_id.
     *
     * @param mixed $source_id the source id
     *
     * @return self
     */
    public function setSourceId($source_id)
    {
        $this->source_id = $source_id;

        return $this;
    }

    /**
     * Gets the value of title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the value of title.
     *
     * @param mixed $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of author.
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the value of author.
     *
     * @param mixed $author the author
     *
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets the value of intro.
     *
     * @return mixed
     */
    public function getIntro()
    {
        return htmlspecialchars_decode($this->intro);
    }

    /**
     * Sets the value of intro.
     *
     * @param mixed $intro the intro
     *
     * @return self
     */
    public function setIntro($intro)
    {
        $this->intro = htmlspecialchars(strip_tags($intro));

        return $this;
    }

    /**
     * Gets the value of content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return htmlspecialchars_decode($this->content);
    }

    /**
     * Sets the value of content.
     *
     * @param mixed $content the content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = htmlspecialchars($content);

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
     * Gets the value of timestamp.
     *
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets the value of timestamp.
     *
     * @param mixed $timestamp the timestamp
     *
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Gets the value of edited.
     *
     * @return mixed
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Sets the value of edited.
     *
     * @param mixed $edited the edited
     *
     * @return self
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Gets the value of approved.
     *
     * @return mixed
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Sets the value of approved.
     *
     * @param mixed $approved the approved
     *
     * @return self
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Gets the value of images.
     *
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the value of images.
     *
     * @param mixed $images the images
     *
     * @return self
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Adds a new image.
     *
     * @param string $image the image url
     *
     * @return self
     */
    public function addImage($image)
    {
        if (!is_array($this->images)) {
            $this->images = [];
        }

        $this->images[] = $image;

        return $this;
    }

    /**
     * Deletes an image.
     *
     * @param string $imageHash the image url hashed
     *
     * @return self
     */
    public function deleteImage($imageHash)
    {
        if (is_array($this->images)) {
            $i = -1;
            foreach ($this->images as $key => $image) {
                if (sha1($image) == $imageHash) {
                    $i = $key;
                    break;
                }
            }

            if ($i >= 0) {
                unset($this->images[$i]);
            }
        }

        return $this;
    }

    /**
     * Gets the value of videos.
     *
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Sets the value of videos.
     *
     * @param mixed $videos the videos
     *
     * @return self
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * Adds a new video.
     *
     * @param string $video the video url
     *
     * @return self
     */
    public function addVideo($video)
    {
        if (!is_array($this->videos)) {
            $this->videos = [];
        }

        $this->videos[] = $video;

        return $this;
    }

    /**
     * Gets the value of likes.
     *
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Sets the value of likes.
     *
     * @param mixed $likes the likes
     *
     * @return self
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Gets the value of shares.
     *
     * @return mixed
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * Sets the value of shares.
     *
     * @param mixed $shares the shares
     *
     * @return self
     */
    public function setShares($shares)
    {
        $this->shares = $shares;

        return $this;
    }

    /**
     * Gets the value of timestamp in a datetime object.
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($this->timestamp);

        return $datetime;
    }

    /**
     * Gets the entire object in an array for batch insert compatibility
     */
    public function toArray()
    {
        return [
            '_id'       => $this->_id,
            'source_id' => $this->source_id,
            'title'     => $this->title,
            'author'    => $this->author,
            'intro'     => $this->intro,
            'content'   => $this->content,
            'url'       => $this->url,
            'timestamp' => $this->timestamp,
            'edited'    => $this->edited,
            'approved'  => $this->approved,
            'images'    => $this->images,
            'videos'    => $this->videos,
            'likes'     => $this->likes,
            'shares'    => $this->shares
        ];
    }
}
