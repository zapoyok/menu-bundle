<?php

namespace Zapoyok\MenuBundle\Admin;

use Zapoyok\MenuBundle\Model\MenuNodeInterface;
use Zapoyok\MenuBundle\Model\MenuNodeManagerInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class MenuNodeAdmin extends AbstractMenuNodeAdmin
{
    protected $menuNodeManager = null;

    public function __construct($code, $class, $baseControllerName, MenuNodeManagerInterface $menuNodeManager)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->menuNodeManager = $menuNodeManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('form.menunode.tab.General')
                ->with('form.menunode.general')->end()
                ->with('form.menunode.parameters', ['class' => 'col-md-8'])->end()
                ->with('form.menunode.position', ['class' => 'col-md-4'])->end()
            ->end()
            ->tab('form.menunode.tab.Rules')
                ->with('form.menunode.rules')->end()
            ->end()
            ->tab('form.menunode.tab.Customization')
                ->with('form.menunode.customization')->end()
            ->end()
        ;

        $formMapper
            ->tab('form.menunode.tab.General')

                ->with('form.menunode.general')
                    ->add('name', 'text', [
                        /* @Ignore */
                        'label' => $this->trans('form.menunode.name', [], 'admin'),
                        /* @Ignore */
                        'help' => $this->trans('help.menunode.name', [], 'admin'),
                    ])
                    ->add('label', 'text', [
                        /* @Ignore */
                        'label' => $this->trans('form.menunode.label', [], 'admin'),
                        /* @Ignore */
                        'help' => $this->trans('help.menunode.label', [], 'admin'),
                    ])
                ->end()
                ->with('form.menunode.parameters')
                     ->add('linkType', 'sonata_type_choice_field_mask', [
                        'choices' => [
                                /* @Ignore */
                                'uri' => $this->trans('form.menunode.uri', [], 'admin'),
                                /* @Ignore */
                                'route' => $this->trans('form.menunode.route', [], 'admin'),
                        ],
                        'map' => [
                            'route' => ['route', 'routeParameters'],
                            'uri'   => ['uri'],
                        ],
                        'empty_value' => 'form.linkType.empty_value',
                        'required'    => false,
                    ])
                    ->add('route', 'text', ['required' => false])
                    ->add('uri', 'text', ['required' => false])

                    ->add('routeParameters', 'zapoyok_extraform_key_value', ['value_type' => 'text'])

                ->end()

                ->with('form.menunode.position')
                    ->add('parent', null,
                        [
                            'class'         => 'ZapoyokMenuBundle:MenuNode',
                            'property'      => 'nameWithLevel',
                            'empty_value'   => 'Choose a parent',
                            'empty_data'    => null,
                            'query_builder' => $this->menuNodeManager->getNodesHierarchyQueryBuilder($this->getParent()->getSubject()),
                        ]
                        )
                    ->add('reorder', 'zapoyok_menu_reorder', ['mapped' => false, 'current_node' => $this->getSubject()])
                ->end()

            ->end()
        ;

        $formMapper
        ->tab('form.menunode.tab.Customization')
            ->with('form.menunode.customization')
                ->add('attributes', 'zapoyok_extraform_key_value', ['value_type' => 'text'])
                ->add('childrenAttributes', 'zapoyok_extraform_key_value', ['value_type' => 'text'])
                ->add('linkAttributes', 'zapoyok_extraform_key_value', ['value_type' => 'text'])
                ->add('labelAttributes', 'zapoyok_extraform_key_value', ['value_type' => 'text'])
            ->end()
        ->end()
        ;

        //parent::configureFormFields($formMapper);
    }

    public function createQuery($context = 'list')
    {
        $admin = $this->isChild() ? $this->getParent() : $this;

        $query = parent::createQuery($context);

        if ($context == 'list' && $this->isChild()) {
            $id = $admin->getRequest()->get('id');
            $query->andWhere($query->getRootAlias() . '.root=:id')->setParameter('id', $id);
            $query->andWhere($query->getRootAlias() . '.id!=:id')->setParameter('id', $id);
        }

        $query->addOrderBy($query->getRootAlias() . '.root', 'ASC');
        $query->addOrderBy($query->getRootAlias() . '.left', 'ASC');

        return $query;
    }

    public function preUpdate($object)
    {
        if (!$object->getParent()) {
            $object->setParent($this->getParent()->getSubject());
        }
    }

    public function prePersist($object)
    {
        if (!$object->getParent()) {
            $object->setParent($this->getParent()->getSubject());
        }
    }

    public function postUpdate($object)
    {
        $this->_reorder($object);
    }

    public function postPersist($object)
    {
        $this->_reorder($object);
    }

     /**
      * @param MenuNodeInterface $object
      */
     private function _reorder($object)
     {
         $reorder = $this->getForm()->get('reorder')->getData();

         if (!in_array($reorder['position'], ['before', 'after'])) {
             return;
         }

         if ($this->menuNodeManager->isSibling($object, $reorder['sibling'])) {
             if ('before' == $reorder['position']) {
                 $this->menuNodeManager->persistAsPrevSiblingOf($object, $reorder['sibling']);
             } elseif ('after' == $reorder['position']) {
                 $this->menuNodeManager->persistAsNextSiblingOf($object, $reorder['sibling']);
             }

             $this->menuNodeManager->save($object);

             $this->getRequest()->getSession()->getFlashBag()->add('success', $this->trans('admin.reorder.success.sibling %position% "%nodename%"', ['%position%' => $reorder['position'], '%nodename%' => $reorder['sibling']->getName()], 'admin'));
         } else {
             $this->getRequest()->getSession()->getFlashBag()->add('error', $this->trans('admin.reorder.failure.notsibling', [], 'admin'));
         }
     }
}
