<?php

namespace ErImageBundle\Entity;

/**
 * Media
 */
class Media
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \ErImageBundle\Entity\Bucket
     */
    private $bucket;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return Media
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Media
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
     * Set bucket
     *
     * @param \ErImageBundle\Entity\Bucket $bucket
     *
     * @return Media
     */
    public function setBucket(\ErImageBundle\Entity\Bucket $bucket = null)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * Get bucket
     *
     * @return \ErImageBundle\Entity\Bucket
     */
    public function getBucket()
    {
        return $this->bucket;
    }
}
