<?php

namespace TaskPlanerBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    private $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('description')
            ->add('date')
            ->add(
                'category',
                'entity',
                [
                    'class' => 'TaskPlanerBundle\Entity\Category',
                    'query_builder' =>function(EntityRepository $er) {
                         return $er->createQueryBuilder('c')->andWhere('c.user = :user')->setParameter('user', $this->user);
                    },
                    'choice_label' => 'title'
                ]
            );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TaskPlanerBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'taskplanerbundle_task';
    }
}
