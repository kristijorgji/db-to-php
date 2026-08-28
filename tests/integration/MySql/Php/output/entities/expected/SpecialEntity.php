<?php

namespace Entities;

class SpecialEntity
{
    /**
     * @var string|null
     */
    private $geometry;

    /**
     * @var string|null
     */
    private $geometryCollection;

    /**
     * @var string|null
     */
    private $json;

    /**
     * @var string|null
     */
    private $lineString;

    /**
     * @var string|null
     */
    private $multilinestring;

    /**
     * @var string|null
     */
    private $point;

    /**
     * @var string|null
     */
    private $multipoint;

    /**
     * @var string|null
     */
    private $polygon;

    /**
     * @var string|null
     */
    private $multyPolygon;

    /**
     * @param string|null $geometry
     * @return $this
     */
    public function setGeometry(?string $geometry)
    {
        $this->geometry = $geometry;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGeometry(): ?string
    {
        return $this->geometry;
    }

    /**
     * @param string|null $geometryCollection
     * @return $this
     */
    public function setGeometryCollection(?string $geometryCollection)
    {
        $this->geometryCollection = $geometryCollection;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGeometryCollection(): ?string
    {
        return $this->geometryCollection;
    }

    /**
     * @param string|null $json
     * @return $this
     */
    public function setJson(?string $json)
    {
        $this->json = $json;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getJson(): ?string
    {
        return $this->json;
    }

    /**
     * @param string|null $lineString
     * @return $this
     */
    public function setLineString(?string $lineString)
    {
        $this->lineString = $lineString;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLineString(): ?string
    {
        return $this->lineString;
    }

    /**
     * @param string|null $multilinestring
     * @return $this
     */
    public function setMultilinestring(?string $multilinestring)
    {
        $this->multilinestring = $multilinestring;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMultilinestring(): ?string
    {
        return $this->multilinestring;
    }

    /**
     * @param string|null $point
     * @return $this
     */
    public function setPoint(?string $point)
    {
        $this->point = $point;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPoint(): ?string
    {
        return $this->point;
    }

    /**
     * @param string|null $multipoint
     * @return $this
     */
    public function setMultipoint(?string $multipoint)
    {
        $this->multipoint = $multipoint;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMultipoint(): ?string
    {
        return $this->multipoint;
    }

    /**
     * @param string|null $polygon
     * @return $this
     */
    public function setPolygon(?string $polygon)
    {
        $this->polygon = $polygon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPolygon(): ?string
    {
        return $this->polygon;
    }

    /**
     * @param string|null $multyPolygon
     * @return $this
     */
    public function setMultyPolygon(?string $multyPolygon)
    {
        $this->multyPolygon = $multyPolygon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMultyPolygon(): ?string
    {
        return $this->multyPolygon;
    }
}
