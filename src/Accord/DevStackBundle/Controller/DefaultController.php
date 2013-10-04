<?php

namespace Accord\DevStackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{
	
	public function indexAction(){
		
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AccordDevStackBundle:Question');
		
		$questions = $repo->createQueryBuilder('q')
			->orderBy('q.created', 'DESC')
			->setMaxResults(20)
			->getQuery()
			->getResult()
		;
		
		return $this->render('AccordDevStackBundle:Default:index.html.twig', array(
			'questions' => $questions
		));
	}
	
	public function questionAction($slug){
		
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AccordDevStackBundle:Question');
		
		$question = $repo->findOneBy(array('slug' => $slug));
		if(!$question) throw $this->createNotFoundException('The specified question could not be found');
		
		return $this->render('AccordDevStackBundle:Question:index.html.twig', array(
			'question' => $question
		));
			
	}
	
	public function solutionVoteAction($id, $voteDirection){
		
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AccordDevStackBundle:Solution');
		
		$solution = $repo->find($id);
		if(!$solution) throw $this->createNotFoundException('The specified solution could not be found');
		
		$user = $this->get('security.context')->getToken()->getUser();
		
		$ballotBox = $this->get('devstack.ballot_box');
		
		if((int)$voteDirection === 1) $ballotBox->upVote($solution, $user);
		elseif((int)$voteDirection === -1) $ballotBox->downVote($solution, $user);
		else throw new \Exception('Invalid vote direction specified');
		
		$this->get('session')->getFlashBag()->add('notice', 'Your vote has been logged');
		
		$url = $this->generateUrl('ds_question', array(
			'slug' => $solution->getQuestion()->getSlug()
		));
		
		return $this->redirect($url);
		
	}
	
}
