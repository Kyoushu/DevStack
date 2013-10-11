<?php

namespace Accord\DevStackBundle\Finder;

use Accord\DevStackBundle\Finder\Factory as FinderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Accord\DevStackBundle\Entity\Tag;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionFinder{
	
	private $finderFactory;
	
	private $tags;
	private $keywords;
	private $pager;
	
	/**
	 *
	 * @Assert\Choice(choices={"created","title","voteCount"})
	 */
	private $orderProperty;
	
	/**
	 *
	 * @Assert\Choice(choices={"asc","desc"})
	 */
	private $orderSort;
	
	public function __construct(FinderFactory $finderFactory){
		
		$this->orderProperty = 'created';
		$this->orderSort = 'desc';
		
		$this->tags = new ArrayCollection();
		$this->keywords = null;
		$this->finderFactory = $finderFactory;
		
		$this->pager = $this->finderFactory
			->getPagerFactory()
			->getPager()
			->setRoute('ds_search')
		;
	}
	
	public function setOrderProperty($orderProperty){
		$this->orderProperty = $orderProperty;
		return $this;
	}
	
	public function setOrderSort($orderSort){
		$this->orderSort = $orderSort;
		return $this;
	}
	
	public function addTag(Tag $tag){
		$this->tags->add($tag);
		return $this;
	}
	
	public function removeTag(Tag $tag){
		$this->tags->removeElement($tag);
		return $this;
	}
	
	public function getTags(){
		return $this->tags;
	}
	
	public function getKeywords(){
		return $this->keywords;
	}
	
	public function setKeywords($keywords){
		$this->keywords = $keywords;
		return $this;
	}
	
	public function getOrderProperty(){
		return $this->orderProperty;
	}
	
	public function getOrderSort(){
		return $this->orderSort;
	}
	
	public function getEntityManager(){
		return $this->finderFactory->getEntityManager();
	}
	
	private function getQueryBuilder(){
		
		$errors = $this->finderFactory->getValidator()->validate($this);
		if(count($errors) > 0) throw new \Exception('Question finder properties contain errors');
		
		$repo = $this->getEntityManager()->getRepository('AccordDevStackBundle:Question');
		$qb = $repo->createQueryBuilder('q');
		
		$qb->select('q, count(v.id) AS voteCount');
		$qb->leftJoin('q.tags', 't');
		$qb->leftJoin('q.solutions', 's');
		$qb->leftJoin('s.votes', 'v');
		
		if($this->tags->count() > 0){
			$tagIds = array();
			foreach($this->tags as $tag){
				$tagIds[] = $tag->getId();
			}
			$qb->andWhere('t.id in (:tagIds)');
			$qb->setParameter('tagIds', $tagIds);
		}
		
		if($this->keywords !== null){
			$keywords = explode(' ', $this->keywords);
			$orX = $qb->expr()->orX();
			foreach($keywords as $index => $keyword){
				
				$placeholder = 'keyword_' . $index;
				$value = '%' . $keyword . '%';
				
				$orX->add("q.title LIKE :{$placeholder}");
				$qb->setParameter($placeholder, $value);
			}
			$qb->andWhere($orX);
		}
		
		return $qb;
		
	}
	
	public function getTotal(){
		return $this->getQueryBuilder()
			->select("count(distinct q.id)")
			->getQuery()
			->getSingleScalarResult()
		;	
	}
	
	public function getResult(){
		
		$aliasProperties = array('voteCount');
		
		$pager = $this->getPager();
		$orderProperty = (in_array($this->orderProperty, $aliasProperties) ? $this->orderProperty : sprintf('q.%s', $this->orderProperty) );
		$orderSort = strtoupper($this->orderSort);
		
		$result = $this->getQueryBuilder()
			->orderBy($orderProperty, $orderSort)
			->setFirstResult( $pager->getOffset() )
			->setMaxResults( $pager->getPerPage() )
			->groupBy('q.id')
			->getQuery()
			->getResult()
		;
		
		return array_map(
			 function($row){ return $row[0]; },
			$result
		);
		
	}
	
	public function getResultParameters(){
		
		$tagSlugs = array();
		foreach($this->tags as $tag){
			$tagSlugs[] = $tag->getSlug();
		}
		
		return array(
			'tagSlugs' => ( count($tagSlugs) > 0 ? implode(',', $tagSlugs) : '-' ),
			'keywords' => ( $this->keywords ? $this->keywords : '-' ),
			'orderProperty' => $this->orderProperty,
			'orderSort' => $this->orderSort
		);
		
	}
	
	public function getResultUrl(){
		return $this->finderFactory
			->getRouter()
			->generate('ds_search', $this->getResultParameters())
		;
	}
	
	public function getPager(){
		return $this->pager
			->setParameters( $this->getResultParameters() )
			->setTotal( $this->getTotal() )
		;
	}
	
}