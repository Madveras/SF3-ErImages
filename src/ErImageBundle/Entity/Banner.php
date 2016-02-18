<?php

namespace ErImageBundle\Entity;

/**
 * Banner
 */
class Banner
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $cottage;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cottage
     *
     * @param string $cottage
     *
     * @return Banner
     */
    public function setCottage($cottage)
    {
        $this->cottage = $cottage;

        return $this;
    }

    /**
     * Get cottage
     *
     * @return string
     */
    public function getCottage()
    {
        return $this->cottage;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Banner
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Banner
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Banner
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Banner
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

