<?php

namespace Accord\DevStackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Solution
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Solution
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
	 * @var Accord\DevStackBundle\Entity\Question
	 * @ORM\ManyToOne(targetEntity="Question", inversedBy="solutions")
     * @ORM\JoinColumn(name="questionId", referencedColumnName="id")
	 */
	private $question;
	
	/**
     * @ORM\OneToMany(targetEntity="SolutionComment", mappedBy="solutionId")
     */
	private $comments;
	
	/**
     * @ORM\OneToMany(targetEntity="SolutionVote", mappedBy="solutionId")
     */
	private $votes;

    /**
     * @var string
     *
     * @ORM\Column(name="solutionMarkdown", type="text")
     */
    private $solutionMarkdown;

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
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
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
     * Set solutionMarkdown
     *
     * @param string $solutionMarkdown
     * @return Solution
     */
    public function setSolutionMarkdown($solutionMarkdown)
    {
        $this->solutionMarkdown = $solutionMarkdown;
    
        return $this;
    }

    /**
     * Get solutionMarkdown
     *
     * @return string 
     */
    public function getSolutionMarkdown()
    {
        return $this->solutionMarkdown;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Solution
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
     * @return Solution
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
     * @return Solution
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
     * Set question
     *
     * @param \Accord\DevStackBundle\Entity\Question $question
     * @return Solution
     */
    public function setQuestion(\Accord\DevStackBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \Accord\DevStackBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
    
    /**
     * Add comments
     *
     * @param \Accord\DevStackBundle\Entity\SolutionComment $comments
     * @return Solution
     */
    public function addComment(\Accord\DevStackBundle\Entity\SolutionComment $comments)
    {
		$comments->setSolution($this);
        $this->comments[] = $comments;
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Accord\DevStackBundle\Entity\SolutionComment $comments
     */
    public function removeComment(\Accord\DevStackBundle\Entity\SolutionComment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add votes
     *
     * @param \Accord\DevStackBundle\Entity\SolutionVote $votes
     * @return Solution
     */
    public function addVote(\Accord\DevStackBundle\Entity\SolutionVote $votes)
    {
		$votes->setSolution($this);
        $this->votes[] = $votes;
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Accord\DevStackBundle\Entity\SolutionVote $votes
     */
    public function removeVote(\Accord\DevStackBundle\Entity\SolutionVote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
	
	/**
	 * 
	 * @return integer
	 */
	public function getScore(){
		$score = 0;
		foreach($this->getVotes() as $vote){
			$score += $vote->getWeight();
		}
		return $score;
	}
}