<?php

namespace Zapoyok\MenuBundle\Form\Type;

use Zapoyok\MenuBundle\Model\MenuManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReorderType extends AbstractType
{
    private $menuManager;

    public function __construct(MenuManagerInterface $menuManager)
    {
        $this->menuManager = $menuManager;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'current_node' => null,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', 'choice', [
            'choices'     => ['before' => 'Before', 'after' => 'After'],
            'empty_value' => 'Choose a position',
        ]);

        $builder->add('sibling', 'entity', [
            'class'         => 'ZapoyokMenuBundle:MenuNode',
            'property'      => 'nameWithLevel',
            'query_builder' => $this->menuManager->getNodesHierarchyQueryBuilder(),
            'empty_value'   => 'Choose a sibling',
        ]);
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'zapoyok_menu_reorder';
    }
}
