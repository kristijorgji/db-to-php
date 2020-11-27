<?php

namespace Entities;

class TimesEntity
{
    /**
     * @var int|null
     */
    private $birthdayYear;

    /**
     * @var string|null
     */
    private $birthdayTime;

    /**
     * @var string|null
     */
    private $column3;

    /**
     * @var string
     */
    private $loggedIn;

    /**
     * @var string|null
     */
    private $registeredDate;

    /**
     * @param int|null $birthdayYear
     * @return $this
     */
    public function setBirthdayYear(?int $birthdayYear)
    {
        $this->birthdayYear = $birthdayYear;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBirthdayYear(): ?int
    {
        return $this->birthdayYear;
    }

    /**
     * @param string|null $birthdayTime
     * @return $this
     */
    public function setBirthdayTime(?string $birthdayTime)
    {
        $this->birthdayTime = $birthdayTime;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBirthdayTime(): ?string
    {
        return $this->birthdayTime;
    }

    /**
     * @param string|null $column3
     * @return $this
     */
    public function setColumn3(?string $column3)
    {
        $this->column3 = $column3;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColumn3(): ?string
    {
        return $this->column3;
    }

    /**
     * @param string $loggedIn
     * @return $this
     */
    public function setLoggedIn(string $loggedIn)
    {
        $this->loggedIn = $loggedIn;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoggedIn(): string
    {
        return $this->loggedIn;
    }

    /**
     * @param string|null $registeredDate
     * @return $this
     */
    public function setRegisteredDate(?string $registeredDate)
    {
        $this->registeredDate = $registeredDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegisteredDate(): ?string
    {
        return $this->registeredDate;
    }
}
