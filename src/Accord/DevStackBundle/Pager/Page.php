<?php

namespace Accord\DevStackBundle\Pager;

use Accord\DevStackBundle\Pager\Pager;

class Page{
	
	private $pager;
	private $page;
	
	public function __construct(Pager $pager, $page){
		$this->pager = $pager;
		$this->page = $page;
	}
	
	public function isCurrent(){
		return $this->page === $this->pager->getPage();
	}
	
	public function isNext(){
		return $this->page === ($this->pager->getPage() + 1);
	}
	
	public function isPrev(){
		return $this->page === ($this->pager->getPage() - 1);
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function getUrl(array $extraParameters = array()){
		
		$route = $this->pager->getRoute();
		$parameters = array_replace(
			$this->pager->getParameters(),
			array(
				'page' => $this->page,
				'perPage' => $this->pager->getPerPage()
			),
			$extraParameters
		);
		
		return $this->pager
			->getPagerFactory()
			->getRouter()
			->generate($route, $parameters)
		;
			
	}
	
}
