<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Livre;
use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_emprunt', DateType::class, [
                "widget" => "single_text",  // pour afficher un input de type date
                "label"  => "Emprunt"
            ])
            ->add('date_retour', DateType::class, [
                "widget" => "single_text",
                "label"  => "Retour",
                "required" => false
            ])
            ->add('livre', EntityType::class, [
                "class" => Livre::class,
                "choice_label" => function(Livre $livre){
                    return $livre->getTitre() . " - " . $livre->getAuteur();
                },
                "placeholder" => "Choisir un livre..."
            ])
            ->add('abonne', EntityType::class, [
                "class" => Abonne::class,
                "choice_label" => "pseudo",
                "placeholder" => ""
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
