<?php
/**
 * Created by PhpStorm.
 * User: nguyenthierry
 * Date: 11/10/2016
 * Time: 16:40
 */
namespace Portfolio\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BlogPicType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', FileType::class, array('data_class' => null));
        $builder->add('title', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Portfolio\Domain\BlogPicture'
        ));
    }

}