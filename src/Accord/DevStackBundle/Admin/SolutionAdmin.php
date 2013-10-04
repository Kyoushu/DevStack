<?php

namespace Accord\DevStackBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SolutionAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper){
		$formMapper
			->add('question', 'entity', array('class' => 'Accord\DevStackBundle\Entity\Question'))
			->add('user', 'entity', array('class' => 'Accord\DevStackBundle\Entity\User'))
			->add('solutionMarkdown', 'text', array('label' => 'Solution'))
		;
    }

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper){
		$datagridMapper
			->add('question')
			->add('user')
		;
    }

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper){
		$listMapper
			->addIdentifier('id')
			->add('question')
			->add('user')
		;
	}
}