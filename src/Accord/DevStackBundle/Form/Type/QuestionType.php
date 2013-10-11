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
		
		$builder->add('tags', 'entity', array(
			'expanded' => true,
			'multiple' => true,
			'class' => 'Accord\DevStackBundle\Entity\Tag',
			'label' => 'Tags'
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