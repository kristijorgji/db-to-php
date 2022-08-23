<?php

namespace Entities;

class BinariusEntity
{
    /**
     * @var string|null
     */
    private $file;

    /**
     * @var string|null
     */
    private $image;

    /**
     * @param string|null $file
     * @return $this
     */
    public function setFile(?string $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
}
