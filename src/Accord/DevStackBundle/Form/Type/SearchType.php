<?php

namespace Accord\DevStackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('tags', 'entity', array(
			'class' => 'Accord\DevStackBundle\Entity\Tag',
			'expanded' => true,
			'multiple' => true
		));
		
		$builder->add('submit', 'submit', array(
			'label' => 'Search'
		));
		
	}
	
	public function getName(){
		return 'questionSearch';
	}
	
}