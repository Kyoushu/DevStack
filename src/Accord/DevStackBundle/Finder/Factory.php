<?php

namespace Accord\DevStackBundle\Finder;

use Doctrine\ORM\EntityManager;
use Accord\DevStackBundle\Finder\QuestionFinder;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Factory{
	
	private $entityManager;
	private $router;
	
	public function __construct(EntityManager $entityManager, Router $router){
		$this->entityManager = $entityManager;
		$this->router = $router;
	}
	
	public function getEntityManager(){
		return $this->entityManager;
	}
	
	public function getRouter(){
		return $this->router;
	}
	
	public function getQuestionFinder(){
		return new QuestionFinder($this);
	}
	
}