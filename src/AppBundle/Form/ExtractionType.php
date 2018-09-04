<?php


namespace AppBundle\Form;

use AppBundle\Entity\Extraction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExtractionType
 *
 * @package AppBundle\Form
 */
class ExtractionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('restaurant', null, ['label' => 'Restaurant', 'required' => true])
            ->add('url', null, ['label' => 'Url', 'required' => true])
            ->add('type', ChoiceType::class, ['label' => 'Extraction Type', 'required' => true, 'choices' => [
                'Web'      => Extraction::TYPE_WEB,
                'Download' => Extraction::TYPE_DOWNLOAD,
                'Api'      => Extraction::TYPE_API]])
            ->add('tag', null, ['label' => 'Tag', 'required' => false, 'choices' => $options['tags']])
            ->add('fileType', null, ['label' => 'File Type', 'required' => false])
            ->add('keyTerms', CollectionType::class, [
                    'entry_type'    => TextType::class,
                    'label'         => 'Key Terms',
                    'required'      => true,
                    'allow_add'     => true]
            )
            ->add('remoteUser', null, ['label' => 'User', 'required' => false])
            ->add('remotePass', null, ['label' => 'Password', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'tags' => null,
        ]);
    }
}
