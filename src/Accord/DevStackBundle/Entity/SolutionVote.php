<?php

namespace Accord\DevStackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SolutionVote
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SolutionVote
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
	 * @ORM\ManyToOne(targetEntity="Solution", inversedBy="votes")
     * @ORM\JoinColumn(name="solutionId", referencedColumnName="id")
	 */
	private $solution;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;


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
     * Set weight
     *
     * @param integer $weight
     * @return SolutionVote
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    
        return $this;
    }

    /**
     * Get weight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set user
     *
     * @param \Accord\DevStackBundle\Entity\User $user
     * @return SolutionVote
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
     * @return SolutionVote
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