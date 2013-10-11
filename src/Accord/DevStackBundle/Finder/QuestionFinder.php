<?php

namespace Accord\DevStackBundle\Finder;

use Accord\DevStackBundle\Finder\Factory;
use Doctrine\Common\Collections\ArrayCollection;
use Accord\DevStackBundle\Entity\Tag;

class QuestionFinder{
	
	private $factory;
	
	private $tags;
	
	public function __construct(Factory $factory){
		$this->tags = new ArrayCollection();
		$this->factory = $factory;
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
	
	public function getEntityManager(){
		return $this->factory->getEntityManager();
	}
	
	public function getRouter(){
		return $this->factory->getRouter();
	}
	
	private function getQueryBuilder(){
		
		$repo = $this->getEntityManager()->getRepository('AccordDevStackBundle:Question');
		$qb = $repo->createQueryBuilder('q');
		$qb->leftJoin('q.tags', 't');
		
		if($this->tags->count() > 0){
			$tagIds = array();
			foreach($this->tags as $tag){
				$tagIds[] = $tag->getId();
			}
			$qb->andWhere('t.id in (:tagIds)');
			$qb->setParameter('tagIds', $tagIds);
		}		
		
		return $qb;
		
	}
	
	public function getResult(){
		return $this->getQueryBuilder()
			->getQuery()
			->getResult()
		;
	}
	
	public function getResultUrl(){
		
		$tagSlugs = array();
		foreach($this->tags as $tag){
			$tagSlugs[] = $tag->getSlug();
		}
		
		return $this->getRouter()->generate('ds_search', array(
			'tagSlugs' => implode(',', $tagSlugs)
		));
		
	}
	
}