<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserAdmin extends Admin
{

    protected $baseRoutePattern = 'user';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('roles' ,'choice' ,array('choices' => $this->getRolesNames(),
                'required'  => true,
                'expanded' => true,
                'mapped' => true,
                'multiple' => true
            ))
            ->add('plainPassword', 'text', ['required' => false])
            ->add('enabled', null, ['required' => false])
            ->end();
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('enabled')
            ->add('email');
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', null, ['route' => ['name' => 'show']])
            ->add('email')
            ->add('emailValid')
            ->add('enabled', null, array('editable' => true))
            ->add('createdAt');

    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->end();
    }
}