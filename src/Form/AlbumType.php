<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 03/10/2016
 * Time: 11:41
 */

namespace Portfolio\Form;

//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;




class AlbumType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', FileType::class, array('data_class' => null));
        $builder->add('title', TextType::class);
        $builder->add('category', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Portfolio\Domain\Album'
        ));
    }

}