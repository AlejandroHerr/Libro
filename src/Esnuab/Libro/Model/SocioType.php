<?php
namespace Esnuab\Libro\Model;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('esncard', 'text', [
                'label' => 'ESNcard'
            ])
            ->add('nombre')
            ->add('apellido')
            ->add('dni')
            ->add('email', 'email')
            ->add('pais', 'country')
            ->add('idioma', 'choice', [
                'choices' => [
                    'es' => 'Castellano',
                    'en' => 'English',
                ]
            ])
            ->add('alta', 'date', [
                'widget' => 'single_text',
                'input' => 'string',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('Crear', 'submit');
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Esnuab\Libro\Model\Socio',
            'csrf_protection'   => true,
        ));
    }
    public function getName()
    {
        return "socio";
    }
}
