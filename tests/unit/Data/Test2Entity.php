<?php declare(strict_types = 1);

namespace kristijorgji\UnitTests\Data;

class Test2Entity
{
    private int $id;
    private ?string $name = null;
    private ?string $surname = null;
    private bool $isWorking;
    private ?float $salary = null;
    private ?float $discount = null;
    private ?float $newColumn = null;
    private ?string $dddd = null;
    private ?string $binaeraylk = null;
    private ?string $f = null;

    /**
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
        return $this;
    }

    public function getIsWorking() : bool
    {
        return $this->isWorking;
    }

    /**
     * @return $this
     */
    public function setSalary(?float $salary)
    {
        $this->salary = $salary;
        return $this;
    }

    public function getSalary() : ?float
    {
        return $this->salary;
    }

    /**
     * @return $this
     */
    public function setDiscount(?float $discount)
    {
        $this->discount = $discount;
        return $this;
    }

    public function getDiscount() : ?float
    {
        return $this->discount;
    }

    /**
     * @return $this
     */
    public function setNewColumn(?float $newColumn)
    {
        $this->newColumn = $newColumn;
        return $this;
    }

    public function getNewColumn() : ?float
    {
        return $this->newColumn;
    }

    /**
     * @return $this
     */
    public function setDddd(?string $dddd)
    {
        $this->dddd = $dddd;
        return $this;
    }

    public function getDddd() : ?string
    {
        return $this->dddd;
    }

    /**
     * @return $this
     */
    public function setBinaeraylk(?string $binaeraylk)
    {
        $this->binaeraylk = $binaeraylk;
        return $this;
    }

    public function getBinaeraylk() : ?string
    {
        return $this->binaeraylk;
    }

    /**
     * @return $this
     */
    public function setF(?string $f)
    {
        $this->f = $f;
        return $this;
    }

    public function getF() : ?string
    {
        return $this->f;
    }
}
