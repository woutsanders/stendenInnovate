<?php
namespace CMSTest\CMSBundle;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;


class Deletephp extends AbstractType
{
    /**
     * @param   \Symfony\Component\Form\FormBuilder $builder
     * @param   array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   //form builder
        $builder->add('UserName');
        $builder->add('save', 'submit', array('attr' => array('class' => 'submit'), 'label' => "Submit"));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CMSTest\CMSBundle\Delete'
        );
    }

    public function getName()
    {
        return 'Deletephp';
    }
}