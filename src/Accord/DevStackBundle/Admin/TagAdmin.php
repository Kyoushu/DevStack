<?php

namespace Accord\DevStackBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TagAdmin extends Admin
{
	// Fields to be shown on create/edit forms
	protected function configureFormFields(FormMapper $formMapper){
		$formMapper
			->add('title')
		;
    }

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper){
		$datagridMapper
			->add('title')
		;
    }

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper){
		$listMapper
			->addIdentifier('title')
		;
	}
}