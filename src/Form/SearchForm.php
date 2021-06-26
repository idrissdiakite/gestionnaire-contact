<?php 
namespace App\Form;

use App\Entity\Category;
use App\Data\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchForm extends AbstractType {

    /**
    * Construction du formulaire
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
       
        $builder 
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un contact'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true 
            ])
        ;
    }
    
    /**
    * Permet de configurer les différentes options du formulaire
    * C'est la classe SearchData qui va représenter nos données
    * 'GET' parcequ'on veut que les paramètres passe dans l'URL pour que l'utilisateur puisse partager une recherche
    * On désactive la csrf prck on est dans un formulaire de recherche
    */
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    /**
    * Pour avoir une URL la plus propre possible 
    */
    public function getBlockPrefix() {
        
        return '';
    }
}