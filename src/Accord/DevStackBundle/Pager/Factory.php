<?php

namespace Accord\DevStackBundle\Pager;

use Symfony\Component\Routing\Router;
use Accord\DevStackBundle\Pager\Pager;
	
class Factory{
	
	private $router;
	
	public function __construct(Router $router){
		$this->router = $router;
	}
	
	public function getRouter(){
		return $this->router;
	}
	
	public function getPager(){
		return new Pager($this);
	}
	
}