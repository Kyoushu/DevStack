<?php

namespace Accord\DevStackBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SolutionVoteAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper){
		$formMapper
			->add('solution', 'entity', array('class' => 'Accord\DevStackBundle\Entity\Solution'))
			->add('user', 'entity', array('class' => 'Accord\DevStackBundle\Entity\User'))
			->add('weight')
		;
    }

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper){
		$datagridMapper
			->add('solution')
			->add('user')
			->add('weight')
		;
    }

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper){
		$listMapper
			->addIdentifier('id')
			->add('solution')
			->add('user')
			->add('weight')
		;
	}
}