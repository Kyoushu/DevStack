<?php

namespace Accord\DevStackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Question
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
     * @ORM\OneToMany(targetEntity="Solution", mappedBy="questionId")
     */
	private $solutions;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="questionMarkdown", type="text")
     */
    private $questionMarkdown;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
	 * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

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
	 * @ORM\ManyToMany(targetEntity="Tag")
	 * @ORM\JoinTable(name="QuestionTag",
	 *		joinColumns={@ORM\JoinColumn(name="questionId", referencedColumnName="id")},
	 *		inverseJoinColumns={@ORM\JoinColumn(name="tagId", referencedColumnName="id")}
	 *		)
	 */
	private $tags;
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set questionMarkdown
     *
     * @param string $questionMarkdown
     * @return Question
     */
    public function setQuestionMarkdown($questionMarkdown)
    {
        $this->questionMarkdown = $questionMarkdown;
    
        return $this;
    }

    /**
     * Get questionMarkdown
     *
     * @return string 
     */
    public function getQuestionMarkdown()
    {
        return $this->questionMarkdown;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Question
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Question
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
     * @return Question
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
	 * @return \Accord\DevStackBundle\Entity\Question
	 */
	public function setUser(User $user){
		$this->user = $user;
		return $this;
	}
	
	/**
	 * Get user
	 * 
	 * @return \Accord\DevStackBundle\Entity\User
	 */
	public function getUser(){
		return $this->user;
	}
    
    /**
     * Add tags
     *
     * @param \Accord\DevStackBundle\Entity\Tag $tags
     * @return Question
     */
    public function addTag(\Accord\DevStackBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Accord\DevStackBundle\Entity\Tag $tags
     */
    public function removeTag(\Accord\DevStackBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add solutions
     *
     * @param \Accord\DevStackBundle\Entity\Solution $solutions
     * @return Question
     */
    public function addSolution(\Accord\DevStackBundle\Entity\Solution $solutions)
    {
		$solutions->setQuestion($this);
        $this->solutions[] = $solutions;
        return $this;
    }

    /**
     * Remove solutions
     *
     * @param \Accord\DevStackBundle\Entity\Solution $solutions
     */
    public function removeSolution(\Accord\DevStackBundle\Entity\Solution $solutions)
    {
        $this->solutions->removeElement($solutions);
    }

    /**
     * Get solutions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSolutions()
    {
        return $this->solutions;
    }
	
	public function getBestSolution(){
		$bestSolution = null;
		foreach($this->getSolutions() as $solution){
			if($bestSolution === null){
				$bestSolution = $solution;
				continue;
			}
			elseif($solution->getScore() > $bestSolution->getScore()){
				$bestSolution = $solution;
			}
		}
		return $bestSolution;
	}
}