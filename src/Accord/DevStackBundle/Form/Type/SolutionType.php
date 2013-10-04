<?php

namespace Accord\DevStackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SolutionType extends AbstractType{
	
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('solutionMarkdown', 'textarea', array(
			'label' => 'Solution'
		));
		
		$builder->add('submit', 'submit', array(
			'label' => 'Post Solution'
		));
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		
		$resolver->setDefaults(array(
			'data_class' => 'Accord\DevStackBundle\Entity\Solution'
		));
		
	}
	
	public function getName(){
		return 'solution';
	}
	
}