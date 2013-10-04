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
			->add('questionMarkdown', 'textarea')
		;
    }

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper){
		$datagridMapper
			->add('title')
			->add('user')
		;
    }

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper){
		$listMapper
			->addIdentifier('title')
			->add('slug')
			->add('user')
		;
	}
}