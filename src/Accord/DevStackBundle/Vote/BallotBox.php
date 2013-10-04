<?php

namespace Accord\DevStackBundle\Vote;

use Doctrine\ORM\EntityManager;

use Accord\DevStackBundle\Entity\Solution;
use Accord\DevStackBundle\Entity\SolutionVote;
use Accord\DevStackBundle\Entity\User;

class BallotBox{
	
	private $em;
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
	
	public function upVote(Solution $solution, User $user){
		$this->removeVote($solution, $user);
		
		$vote = new SolutionVote();
		$vote->setSolution($solution);
		$vote->setUser($user);
		$vote->setWeight(1);
		
		$this->em->persist($vote);
		$this->em->flush();
	}
	
	public function downVote(Solution $solution, User $user){
		$this->removeVote($solution, $user);
		
		$vote = new SolutionVote();
		$vote->setSolution($solution);
		$vote->setUser($user);
		$vote->setWeight(-1);
		
		$this->em->persist($vote);
		$this->em->flush();
	}
	
	public function removeVote(Solution $solution, User $user){
		
		$repo = $this->em->getRepository('AccordDevStackBundle:SolutionVote');
		$vote = $repo->findOneBy(array(
			'solution' => $solution->getId(),
			'user' => $user->getId()
		));
		
		if(!$vote) return false;
		
		$this->em->remove($vote);
		$this->em->flush();
		
		return true;
		
	}
	
}