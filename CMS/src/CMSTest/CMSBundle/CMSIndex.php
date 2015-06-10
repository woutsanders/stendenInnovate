<?php
namespace CMSTest\CMSBundle;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;


class CMSIndex extends AbstractType
{
    /**
     * @param   \Symfony\Component\Form\FormBuilder $builder
     * @param   array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   //form builder
        $builder->add('UserName');
        $builder->add('Status', 'choice', array(
        'choices' => array('ready' => 'ready', 'waitingForReady' => 'waiting for ready', 'waiting' => 'waiting', 'playing' => 'playing', 'finished' => 'finished'),
        'required' => true,
    ));
        $builder->add('save', 'submit', array('attr' => array('class' => 'submit'), 'label' => "Submit"));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CMSTest\CMSBundle\Editstatus'
        );
    }

    public function getName()
    {
        return 'CMSIndex';
    }
}