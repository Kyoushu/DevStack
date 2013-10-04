<?php

namespace Accord\DevStackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SolutionCommentType extends AbstractType{
	
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('commentMarkdown', 'textarea', array(
			'label' => 'Comment'
		));
		
		$builder->add('submit', 'submit', array(
			'label' => 'Post Comment'
		));
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		
		$resolver->setDefaults(array(
			'data_class' => 'Accord\DevStackBundle\Entity\SolutionComment'
		));
		
	}
	
	public function getName(){
		return 'solutionComment';
	}
	
}