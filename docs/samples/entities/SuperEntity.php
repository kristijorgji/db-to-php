<?php

namespace Entities;

class SuperEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $payload;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $superStatus;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $time;

    /**
     * @var int|null
     */
    private $canBeNulled;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return string
     */
    public function getEvent() : string
    {
        return $this->event;
    }

    /**
     * @param string $payload
     * @return $this
     */
    public function setPayload(string $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayload() : string
    {
        return $this->payload;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * @param string $superStatus
     * @return $this
     */
    public function setSuperStatus(string $superStatus)
    {
        $this->superStatus = $superStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuperStatus() : string
    {
        return $this->superStatus;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive() : bool
    {
        return $this->active;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile(string $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * @param string $time
     * @return $this
     */
    public function setTime(string $time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getTime() : string
    {
        return $this->time;
    }

    /**
     * @param int|null $canBeNulled
     * @return $this
     */
    public function setCanBeNulled(?int $canBeNulled)
    {
        $this->canBeNulled = $canBeNulled;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCanBeNulled() : ?int
    {
        return $this->canBeNulled;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt() : string
    {
        return $this->createdAt;
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt() : string
    {
        return $this->updatedAt;
    }
}
