<?php

namespace Accord\DevStackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class YammerController extends Controller{
	
	public function loginAction(){
		$yammer = $this->get('devstack.oath.yammer');
		$url = $yammer->getService()->getAuthorizationUri();
		
		// The OAuth service class appears to handle sending headers, so we'll
		// 
		header('Location: ' . $url);
		die();
	}
	
	public function returnAction(Request $request){
		
	}
	
}