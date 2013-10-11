<?php

namespace Accord\DevStackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Accord\DevStackBundle\Form\Type\SolutionCommentType;
use Accord\DevStackBundle\Form\Type\SolutionType;
use Accord\DevStackBundle\Form\Type\QuestionType;
use Accord\DevStackBundle\Form\Type\SearchType;

use Accord\DevStackBundle\Entity\SolutionComment;
use Accord\DevStackBundle\Entity\Solution;
use Accord\DevStackBundle\Entity\Question;

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
		
		$solutionForm = $this->createForm(new SolutionType(), null, array(
			'action' => $this->generateUrl('ds_solution_post', array(
				'slug' => $slug
			))
		));
		
		$commentForms = array();
		foreach($question->getSolutions() as $solution){
			$commentForms[$solution->getId()] = $this
				->createForm(new SolutionCommentType(), null, array(
					'action' => $this->generateUrl('ds_solution_comment', array(
						'id' => $solution->getId()
					))
				))
				->createView()
			;
		}
		
		return $this->render('AccordDevStackBundle:Question:index.html.twig', array(
			'question' => $question,
			'commentForms' => $commentForms,
			'solutionForm' => $solutionForm->createView()
		));
			
	}

	public function questionNewAction(Request $request){
	
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		
		$question = new Question();
		$question->setUser($user);
		
		$form = $this->createForm(new QuestionType(), $question, array(
			'action' => $this->generateUrl('ds_question_new')
		));
		
		if($request->isMethod('POST')){
			$form->handleRequest($request);
			if($form->isValid()){
				$em->persist($question);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('notice', 'Your question has been posted');
				
				$url = $this->generateUrl('ds_question', array(
					'slug' => $question->getSlug()
				));
				
				return $this->redirect($url);
			}
		}
		
		return $this->render('AccordDevStackBundle:Question:new.html.twig', array(
			'question' => $question,
			'form' => $form->createView()
		));
	
	}
	
	public function questionDeleteAction($slug, $confirm){
		
		$em = $this->getDoctrine()->getManager();
		$question = $em->getRepository('AccordDevStackBundle:Question')->findOneBy(array(
			'slug' => $slug
		));
		
		if(!$question) throw $this->createNotFoundException ('The specified question could not be found');
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $question->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to delete this question');
		}
		
		$questionUrl = $this->generateUrl('ds_question', array('slug' => $slug));
		$confirmUrl = $this->generateUrl('ds_question_delete', array(
			'slug' => $slug,
			'confirm' => 'confirm'
		));
		
		$flashBag = $this->get('session')->getFlashBag();
		
		if($confirm){
			$em->remove($question);
			$em->flush();
			$flashBag->add('notice', 'Question deleted');
			
			$homeUrl = $this->generateUrl('ds_homepage');
			return $this->redirect($homeUrl);
		}
		else{
			
			return $this->forward('AccordDevStackBundle:Default:confirm', array(
				'title' => 'Delete Question',
				'message' => 'Are you sure you want to delete this question?',
				'confirmUrl' => $confirmUrl,
				'cancelUrl' => $questionUrl
			));
			
		}
		
	}
	
	public function questionEditAction(Request $request, $slug){
		
		$em = $this->getDoctrine()->getManager();
		$question = $em->getRepository('AccordDevStackBundle:Question')->findOneBy(array(
			'slug' => $slug
		));
		
		if(!$question) throw $this->createNotFoundException ('The specified question could not be found');
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $question->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to edit this question');
		}
		
		$form = $this->createForm(new QuestionType(), $question, array(
			'action' => $this->generateUrl('ds_question_edit', array(
				'slug' => $slug
			))
		));
                
                $form->add('cancel', 'submit');
		
		if($request->isMethod('POST')){
			
			$form->handleRequest($request);
			
			$url = $this->generateUrl('ds_question', array(
				'slug' => $slug
			));
			
			if($form->get('cancel')->isClicked()){
				return $this->redirect($url);
			}
			
			if($form->isValid()){
				$em->persist($question);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('notice', 'Your question has been updated');
				
				return $this->redirect($url);
			}
			
		}
                		
		return $this->render('AccordDevStackBundle:Question:edit.html.twig', array(
			'question' => $question,
			'form' => $form->createView()
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
	
	public function solutionCommentAction(Request $request, $id){
		
		$em = $this->getDoctrine()->getManager();
		
		$solution = $em->getRepository('AccordDevStackBundle:Solution')->find($id);
		if(!$solution) throw $this->createNotFoundException('The specified solution could not be found');
		
		$user = $this->get('security.context')->getToken()->getUser();
		
		$comment = new SolutionComment();
		$comment->setSolution($solution);
		$comment->setUser($user);
		$form = $this->createForm(new SolutionCommentType(), $comment);
		
		$form->handleRequest($request);
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$redirectUrl = $this->generateUrl('ds_question', array(
			'slug' => $solution->getQuestion()->getSlug()
		));
		
		if(!$form->isValid()){
			$flashBag->add('error', 'There was a problem adding your comment');
			return $this->redirect($redirectUrl);
		}
		
		$em->persist($comment);
		$em->flush();
		
		$flashBag->add('notice', 'Your comment has been added');
		
		return $this->redirect($redirectUrl);
		
	}
	
	public function solutionEditAction(Request $request, $id){
		
		$em = $this->getDoctrine()->getManager();
		$solution = $em->getRepository('AccordDevStackBundle:Solution')->find($id);
		
		if(!$solution) throw $this->createNotFoundException('The specified solution could not be found');
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $solution->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to edit this solution');
		}
		
		$form = $this->createForm(new SolutionType, $solution, array(
			'action' => $this->generateUrl('ds_solution_edit', array('id' => $id))
		));
		
		$form->add('cancel', 'submit');
		
		$questionUrl = $this->generateUrl('ds_question', array(
			'slug' => $solution->getQuestion()->getSlug()
		));
		
		if($request->isMethod('POST')){
			$form->handleRequest($request);
			
			if($form->get('cancel')->isClicked()){
				return $this->redirect($questionUrl);
			}
			
			if($form->isValid()){
				$em->persist($solution);
				$em->flush();
				$flashBag->add('notice', 'Your solution has been updated');
				return $this->redirect($questionUrl);
			}
			else{
				$flashBag->add('error', 'There was a problem updating your solution');
			}
			
		}
		
		return $this->render('AccordDevStackBundle:Solution:edit.html.twig', array(
			'solution' => $solution,
			'form' => $form->createView()
		));
		
	}
	
	public function solutionDeleteAction($id, $confirm){
		
		$em = $this->getDoctrine()->getManager();
		$solution = $em->getRepository('AccordDevStackBundle:Solution')->find($id);
		
		if(!$solution) throw $this->createNotFoundException('The specified solution could not be found');
		
		$question = $solution->getQuestion();
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $solution->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to delete this solution');
		}
		
		$questionUrl = $this->generateUrl('ds_question', array('slug' => $question->getSlug()));
		$confirmUrl = $this->generateUrl('ds_solution_delete', array(
			'id' => $id,
			'confirm' => 'confirm'
		));
		
		if($confirm){
			$em->remove($solution);
			$em->flush();
			$flashBag->add('notice', 'Solution deleted');
			return $this->redirect($questionUrl);
		}
		else{
			
			return $this->forward('AccordDevStackBundle:Default:confirm', array(
				'title' => 'Delete Solution',
				'message' => 'Are you sure you want to delete this solution?',
				'confirmUrl' => $confirmUrl,
				'cancelUrl' => $questionUrl
			));
			
		}
		
	}
	
	public function solutionPostAction(Request $request, $slug){
		
		$em = $this->getDoctrine()->getManager();
		$question = $em->getRepository('AccordDevStackBundle:Question')->findOneBy(array('slug' => $slug));
		
		if(!$question) throw $this->createNotFoundException('The specified question could not be found');
		
		$user = $this->get('security.context')->getToken()->getUser();
		
		$solution = new Solution();
		$solution->setUser($user);
		$solution->setQuestion($question);
		
		$form = $this->createForm(new SolutionType(), $solution);
		$form->handleRequest($request);
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$questionUrl = $this->generateUrl('ds_question', array(
			'slug' => $slug
		));
		
		if(!$form->isValid()){
			$flashBag->add('error', 'There was a problem adding your solution');
			return $this->redirect($questionUrl);
		}
		
		$em->persist($solution);
		$em->flush();
		$flashBag->add('notice', 'Solution added');
		return $this->redirect($questionUrl);
		
		
	}
	
	public function solutionCommentEditAction(Request $request, $id){
		
		$em = $this->getDoctrine()->getManager();
		$comment = $em->getRepository('AccordDevStackBundle:SolutionComment')->find($id);
		
		if(!$comment) throw $this->createNotFoundException('The specified comment could not be found');
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $comment->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to edit this comment');
		}
		
		$form = $this->createForm(new SolutionCommentType, $comment, array(
			'action' => $this->generateUrl('ds_solution_comment_edit', array('id' => $id))
		));
		
		$form->add('cancel', 'submit');
		
		$questionUrl = $this->generateUrl('ds_question', array(
			'slug' => $comment->getSolution()->getQuestion()->getSlug()
		));
		
		if($request->isMethod('POST')){
			$form->handleRequest($request);
			
			if($form->get('cancel')->isClicked()){
				return $this->redirect($questionUrl);
			}
			
			if($form->isValid()){
				$em->persist($comment);
				$em->flush();
				$flashBag->add('notice', 'Your comment has been updated');
				return $this->redirect($questionUrl);
			}
			else{
				$flashBag->add('error', 'There was a problem updating your comment');
			}
			
		}
		
		return $this->render('AccordDevStackBundle:SolutionComment:edit.html.twig', array(
			'comment' => $comment,
			'form' => $form->createView()
		));
		
	}
	
	public function solutionCommentDeleteAction($id, $confirm){
		
		$em = $this->getDoctrine()->getManager();
		$comment = $em->getRepository('AccordDevStackBundle:SolutionComment')->find($id);
		
		if(!$comment) throw $this->createNotFoundException('The specified comment could not be found');
		
		$question = $comment->getSolution()->getQuestion();
		
		$flashBag = $this->get('session')->getFlashBag();
		
		$user = $this->get('security.context')->getToken()->getUser();
		if($user->getId() !== $comment->getUser()->getId()){
			throw new AccessDeniedException('You do not have permission to delete this comment');
		}
		
		$questionUrl = $this->generateUrl('ds_question', array('slug' => $question->getSlug()));
		$confirmUrl = $this->generateUrl('ds_solution_comment_delete', array(
			'id' => $id,
			'confirm' => 'confirm'
		));
		
		if($confirm){
			$em->remove($comment);
			$em->flush();
			$flashBag->add('notice', 'Comment deleted');
			return $this->redirect($questionUrl);
		}
		else{
			
			return $this->forward('AccordDevStackBundle:Default:confirm', array(
				'title' => 'Delete Comment',
				'message' => 'Are you sure you want to delete this comment?',
				'confirmUrl' => $confirmUrl,
				'cancelUrl' => $questionUrl
			));
			
		}
		
	}
	
	public function confirmAction($title, $message, $confirmUrl, $cancelUrl){
		
		return $this->render('AccordDevStackBundle:Default:confirm.html.twig', array(
			'title' => $title,
			'message' => $message,
			'confirmUrl' => $confirmUrl,
			'cancelUrl' => $cancelUrl
		));
		
	}
	
	public function searchAction(Request $request, $tagSlugs){
		
		$finder = $this->get('devstack.finder_factory')->getQuestionFinder();
		
		if($tagSlugs){
			$tags = $this->getDoctrine()->getManager()
				->getRepository('AccordDevStackBundle:Tag')
				->findBy(array(
					'slug' => explode(',', $tagSlugs)
				))
			;
			foreach($tags as $tag){
				$finder->addTag($tag);
			}
		}
		
		$form = $this->createForm(new SearchType(), $finder, array(
			'action' => $this->generateUrl('ds_search')
		));
		
		if($request->isMethod('POST')){
			$form->handleRequest($request);
			return $this->redirect( $finder->getResultUrl() );
		}
		
		return $this->render('AccordDevStackBundle:Search:index.html.twig', array(
			'finder' => $finder,
			'form' => $form->createView()
		));
		
	}
	
}
