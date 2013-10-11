<?php

namespace Accord\DevStackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class QuestionSearchType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder->add('keywords', 'text');
		
		$builder->add('tags', 'entity', array(
			'class' => 'Accord\DevStackBundle\Entity\Tag',
			'expanded' => true,
			'multiple' => true,
			'query_builder' => function(EntityRepository $er){
				return $er->createQueryBuilder('t')
					->orderBy('t.title', 'ASC')
				;
			}
		));
		
		$builder->add('orderProperty', 'choice', array(
			'label' => 'Order By',
			'choices' => array(
				'title' => 'Title',
				'created' => 'Created',
				'voteCount' => 'Votes'
			)
		));
		
		$builder->add('orderSort', 'choice', array(
			'label' => 'Sort',
			'choices' => array(
				'asc' => 'Ascending',
				'desc' => 'Descending'
			)
		));
		
		$builder->add('submit', 'submit', array(
			'label' => 'Search'
		));
		
	}
	
	public function getName(){
		return 'questionSearch';
	}
	
}