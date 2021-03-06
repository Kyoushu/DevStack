<?php

namespace Accord\DevStackBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class QuestionAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper){
		$formMapper
			->add('user', 'entity', array('class' => 'Accord\DevStackBundle\Entity\User'))
			->add('title', 'text')
			->add('questionMarkdown', 'textarea', array('label' => 'Question'))
			->add('tags', 'collection', array(
				'type' => 'entity',
				'options' => array(
					'class' => 'Accord\DevStackBundle\Entity\Tag',
					'label' => false
				),
				'allow_add' => true,
				'allow_delete' => true
			))
		;
    }

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper){
		$datagridMapper
			->add('title')
			->add('user')
			->add('tags')
			->add('created')
			->add('updated')
		;
    }

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper){
		$listMapper
			->add('user')
			->addIdentifier('title')
			->add('created')
			->add('updated')
			->add('tags')
		;
	}
}