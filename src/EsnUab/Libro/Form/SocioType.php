<?php

namespace EsnUab\Libro\Form;

use EsnUab\Libro\Model\Map\SocioTableMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocioType extends AbstractType
{
    const SUBMIT_ACTION_CREAR = 'crear';
    const SUBMIT_ACTION_EDITAR = 'editar';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('esncard', 'text', [
                'label' => 'ESNcard',
            ])
            ->add('nombre')
            ->add('apellido')
            ->add('dni')
            ->add('email', 'email')
            ->add('pais', 'country')
            ->add('idioma', 'choice', [
                'choices' => $this->getIdiomaValues(),
            ])
            ->add('alta', 'date', [
                'widget' => 'single_text',
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
            ]);

        if ($options['action_type'] === self::SUBMIT_ACTION_CREAR) {
            $builder->add('Crear', 'submit', ['attr' => ['class' => 'btn-success']]);
        } elseif ($options['action_type'] === self::SUBMIT_ACTION_EDITAR) {
            $builder->add('version_comment', 'text', [
                    'label' => 'Comentario',
                ])
                ->add('Guardar', 'submit', ['attr' => ['class' => 'btn-warning']]);
        }
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EsnUab\\Libro\\Model\\Socio',
            'csrf_protection' => true,
            'action_type' => 'crear',
        ));
    }
    public function getName()
    {
        return 'socio';
    }

    private function getIdiomaValues()
    {
        return SocioTableMap::getValueSet(SocioTableMap::COL_IDIOMA);
    }
}
