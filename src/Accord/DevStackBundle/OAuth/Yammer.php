<?php

namespace Accord\DevStackBundle\OAuth;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Storage\Session;

use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\Container;

class Yammer{
	
	private $router;
	private $container;
	
	private $parameters;
	
	private $service;
	
	public function __construct(Router $router, Container $container){
		
		$this->router = $router;
		$this->container = $container;
		
		$this->parameters = $container->getParameter('devstack.oauth.yammer');
		
		// __________ Create service
		
		$serviceFactory = new \OAuth\ServiceFactory();
		
		$credentials = new Credentials(
			$this->parameters['clientId'],
			$this->parameters['clientSecret'],
			$this->getReturnUrl()
		);
		
		$storage = new Session();
		
		$this->service = $serviceFactory->createService('yammer', $credentials, $storage, $this->parameters['scope']);

	}
	
	public function getReturnUrl(){
		$schemeHost = $this->container->get('request')->getSchemeAndHttpHost();
		$url = $this->router->generate('ds_login_yammer_return');
		return $schemeHost . $url;
	}
	
	public function getService(){
		return $this->service;
	}
	
}