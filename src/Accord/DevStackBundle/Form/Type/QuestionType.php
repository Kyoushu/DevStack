<?php

namespace Accord\DevStackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('title', 'text');
		
		$builder->add('questionMarkdown', 'textarea', array(
			'label' => 'Question'
		));
		
		$builder->add('tags', 'collection', array(
			'type' => 'entity',
			'prototype' => true,
			'allow_add' => true,
			'allow_delete' => true,
			'options' => array(
				'property' => 'title',
				'class' => 'Accord\DevStackBundle\Entity\Tag',
				'label' => false
			)
		));
		
		$builder->add('submit', 'submit', array(
			'label' => 'Post Question'
		));
		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		
		$resolver->setDefaults(array(
			'data_class' => 'Accord\DevStackBundle\Entity\Question'
		));
		
	}
	
	public function getName(){
		return 'question';
	}
	
}