<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Data;

use kristijorgji\DbToPhp\Data\AbstractEntity;

class TestPseudoModelEntity extends AbstractEntity
{
    protected int $id = 0;
    protected ?string $name = null;
    protected ?string $surname = null;
    protected bool $isWorking = false;

    /**
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        $this->track('id', $id);
        return $this;
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        $this->track('name', $name);
        return $this;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setSurname(?string $surname)
    {
        $this->surname = $surname;
        $this->track('surname', $surname);
        return $this;
    }

    public function getSurname() : ?string
    {
        return $this->surname;
    }

    /**
     * @return $this
     */
    public function setIsWorking(bool $isWorking)
    {
        $this->isWorking = $isWorking;
        $this->track('isWorking', $isWorking);
        return $this;
    }

    public function getIsWorking() : bool
    {
        return $this->isWorking;
    }
}
