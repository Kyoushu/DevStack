<?php

namespace Accord\DevStackBundle\Finder;

use Doctrine\ORM\EntityManager;
use Accord\DevStackBundle\Finder\QuestionFinder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Accord\DevStackBundle\Pager\Factory as PagerFactory;
use Symfony\Component\Validator\Validator;

class Factory{
	
	private $entityManager;
	private $router;
	private $pagerFactory;
	private $validator;
	
	public function __construct(EntityManager $entityManager, Router $router, PagerFactory $pagerFactory, Validator $validator){
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->pagerFactory = $pagerFactory;
		$this->validator = $validator;
	}
	
	public function getEntityManager(){
		return $this->entityManager;
	}
	
	public function getRouter(){
		return $this->router;
	}
	
	public function getPagerFactory(){
		return $this->pagerFactory;
	}
	
	public function getValidator(){
		return $this->validator;
	}
	
	public function getQuestionFinder(){
		return new QuestionFinder($this);
	}
	
}