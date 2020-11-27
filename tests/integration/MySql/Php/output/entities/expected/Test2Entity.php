<?php

namespace Entities;

class Test2Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var bool
     */
    private $isWorking;

    /**
     * @var float|null
     */
    private $salary;

    /**
     * @var float|null
     */
    private $discount;

    /**
     * @var float|null
     */
    private $newColumn;

    /**
     * @var string|null
     */
    private $dddd;

    /**
     * @var string|null
     */
    private $binaeraylk;

    /**
     * @var string|null
     */
    private $f;

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
    public function getId(): int
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
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
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
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsWorking(): bool
    {
        return $this->isWorking;
    }

    /**
     * @param float|null $salary
     * @return $this
     */
    public function setSalary(?float $salary)
    {
        $this->salary = $salary;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getSalary(): ?float
    {
        return $this->salary;
    }

    /**
     * @param float|null $discount
     * @return $this
     */
    public function setDiscount(?float $discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float|null $newColumn
     * @return $this
     */
    public function setNewColumn(?float $newColumn)
    {
        $this->newColumn = $newColumn;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getNewColumn(): ?float
    {
        return $this->newColumn;
    }

    /**
     * @param string|null $dddd
     * @return $this
     */
    public function setDddd(?string $dddd)
    {
        $this->dddd = $dddd;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDddd(): ?string
    {
        return $this->dddd;
    }

    /**
     * @param string|null $binaeraylk
     * @return $this
     */
    public function setBinaeraylk(?string $binaeraylk)
    {
        $this->binaeraylk = $binaeraylk;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBinaeraylk(): ?string
    {
        return $this->binaeraylk;
    }

    /**
     * @param string|null $f
     * @return $this
     */
    public function setF(?string $f)
    {
        $this->f = $f;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getF(): ?string
    {
        return $this->f;
    }
}
