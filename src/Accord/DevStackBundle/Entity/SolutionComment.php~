<?php

namespace Accord\DevStackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SolutionComment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SolutionComment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	 *
	 * @var Accord\DevStackBundle\Entity\User
	 * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 *
	 * @var Accord\DevStackBundle\Entity\Solution
	 * @ORM\ManyToOne(targetEntity="Solution", inversedBy="comments")
     * @ORM\JoinColumn(name="solutionId", referencedColumnName="id")
	 */
	private $solution;

    /**
     * @var string
     *
     * @ORM\Column(name="commentMarkdown", type="text")
     */
    private $commentMarkdown;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
	 * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
	 * @Gedmo\Timestampable(on="update")
     */
    private $updated;


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
     * Set commentMarkdown
     *
     * @param string $commentMarkdown
     * @return SolutionComment
     */
    public function setCommentMarkdown($commentMarkdown)
    {
        $this->commentMarkdown = $commentMarkdown;
    
        return $this;
    }

    /**
     * Get commentMarkdown
     *
     * @return string 
     */
    public function getCommentMarkdown()
    {
        return $this->commentMarkdown;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return SolutionComment
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return SolutionComment
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set user
     *
     * @param \Accord\DevStackBundle\Entity\User $user
     * @return SolutionComment
     */
    public function setUser(\Accord\DevStackBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Accord\DevStackBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set solution
     *
     * @param \Accord\DevStackBundle\Entity\Solution $solution
     * @return SolutionComment
     */
    public function setSolution(\Accord\DevStackBundle\Entity\Solution $solution = null)
    {
        $this->solution = $solution;
    
        return $this;
    }

    /**
     * Get solution
     *
     * @return \Accord\DevStackBundle\Entity\Solution 
     */
    public function getSolution()
    {
        return $this->solution;
    }
}