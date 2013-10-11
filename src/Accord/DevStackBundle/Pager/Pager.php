<?php

namespace Accord\DevStackBundle\Pager;

use Accord\DevStackBundle\Pager\Factory as PagerFactory;
use Accord\DevStackBundle\Pager\Page;

class Pager implements \Iterator{
	
	private $perPage;
	private $page;
	private $route;
	private $parameters;
	private $total;
	private $overflowLeft;
	private $overflowRight;
	
	private $pagerFactory;
	private $iteratorArray;
	
	public function __construct(PagerFactory $pagerFactory){
		
		$this->iteratorArray = null;
		$this->pagerFactory = $pagerFactory;
		
		$this->perPage = 20;
		$this->page = 1;
		$this->route = null;
		$this->parameters = array();
		$this->total = 0;
		$this->overflowLeft = 5;
		$this->overflowRight = 5;
		
	}
	
	public function getPagerFactory(){
		return $this->pagerFactory;
	}
	
	public function setPerPage($perPage){
		$this->perPage = (int)$perPage;
		if($this->perPage < 1) $this->perPage = 1;
		return $this;
	}
	
	public function setPage($page){
		$this->page = (int)$page;
		if($this->page < 1) $this->page = 1;
		return $this;
	}
	
	public function setRoute($route){
		$this->route = $route;
		return $this;
	}
	
	public function setParameters(array $parameters){
		$this->parameters = $parameters;
		return $this;
	}
	
	public function setTotal($total){
		$this->total = (int)$total;
		if($this->total < 0) $this->total = 0;
		return $this;
	}
	
	public function setOverflowLeft($overflowLeft){
		$this->overflowLeft = (int)$overflowLeft;
		if($this->overflowLeft < 0) $this->overflowLeft = 0;
		return $this;
	}
	
	public function setOverflowRight($overflowRight){
		$this->overflowRight = (int)$overflowRight;
		if($this->overflowRight < 0) $this->overflowRight = 0;
		return $this;
	}
	
	public function getPerPage(){
		return $this->perPage;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function getOffset(){
		$perPage = $this->getPerPage();
		$page = $this->getPage();
		return ($page - 1) * $perPage;
	}
	
	public function getFirstItemPosition(){
		if($this->getTotal() === 0) return 0;
		return $this->getOffset() + 1;
	}
	
	public function getLastItemPosition(){
		if($this->getTotal() === 0) return 0;
		$first = $this->getFirstItemPosition();
		$count = $this->getCurrentPageItemCount();
		return $first + ($count - 1);
	}
	
	private function getCurrentPageItemCount(){
		$total = $this->getTotal();
		$offset = $this->getOffset();
		$perPage = $this->getPerPage();
		
		$remain = $total - $offset;
		return ($remain < $perPage ? $remain : $perPage);
	}
	
	public function getRoute(){
		return $this->route;
	}
	
	public function getParameters(){
		return $this->parameters;
	}
	
	public function getTotal(){
		return $this->total;
	}
	
	public function getOverflowLeft(){
		return $this->overflowLeft;
	}
	
	public function getOverflowRight(){
		return $this->overflowRight;
	}
	
	public function getTotalPages(){
		$pages = ceil($this->total / $this->perPage);
		if($pages < 1) return 1;
		return $pages;
	}
	
	public function getStartPage(){
		$page = ($this->getPage() - $this->getOverflowLeft());
		if($page < 1) return 1;
		return $page;
	}
	
	public function getEndPage(){
		$page = ($this->getPage() + $this->getOverflowRight());
		$totalPages = $this->getTotalPages();
		if($page > $totalPages) return $totalPages;
		return $page;
	}
	
	private function generateIteratorArray(){
		if($this->iteratorArray !== null) return;
		
		$startPage = $this->getStartPage();
		$endPage = $this->getEndPage();
		
		$this->iteratorArray = array();
		for($page = $startPage; $page <= $endPage; $page++){
			$this->iteratorArray[$page] = new Page($this, $page);
		}
		
	}
	
	public function getIterator(){
		$this->generateIteratorArray();
		return $this->iteratorArray;
	}
	
	// _________________ Iterator implementation
	
	public function rewind(){
		$this->generateIteratorArray();
		reset($this->iteratorArray);
	}
	
	public function current(){
		$this->generateIteratorArray();
		return current($this->iteratorArray);
	}
	
	public function key(){
		$this->generateIteratorArray();
		return key($this->iteratorArray);
	}
	
	public function next(){
		$this->generateIteratorArray();
		return next($this->iteratorArray);
	}
	
	public function valid(){
		$this->generateIteratorArray();
		return (bool)current($this->iteratorArray);
	}
	
}