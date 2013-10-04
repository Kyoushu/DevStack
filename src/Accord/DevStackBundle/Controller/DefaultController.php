<?php

namespace Accord\DevStackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{
	
	public function indexAction(){
		
		$formBuilder = $this->createFormBuilder(null, array(
			'action' => $this->generateUrl('ds_homepage')
		));
		
		$formBuilder->add('text', 'text');
		$formBuilder->add('email', 'email');
		$formBuilder->add('textarea', 'textarea');
		$formBuilder->add('checkbox', 'checkbox');
		$formBuilder->add('submit', 'submit');
		
		$form = $formBuilder->getForm();
		
		return $this->render('AccordDevStackBundle:Default:test.html.twig', array(
			'form' => $form->createView()
		));
	}
	
}
