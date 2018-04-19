<?php

namespace kristijorgji\UnitTests\Data;

use kristijorgji\DbToPhp\Data\AbstractEntity;

class TestPseudoModelEntity extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $surname;

    /**
     * @var bool
     */
    protected $isWorking;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        $this->track('id', $id);
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
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        $this->track('name', $name);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $surname
     * @return $this
     */
    public function setSurname(?string $surname)
    {
        $this->surname = $surname;
        $this->track('surname', $surname);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname() : ?string
    {
        return $this->surname;
    }

    /**
     * @param bool $isWorking
     * @return $this
     */
    public function setIsWorking(bool $isWorking)
    {
        $this->isWorking = $isWorking;
        $this->track('isWorking', $isWorking);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsWorking() : bool
    {
        return $this->isWorking;
    }
}
