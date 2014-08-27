<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class Word extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('word');

        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Word');
        $this->setHydrator($hydrator);

        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $this->add($id);

        $source = new Element\Text('source');
        $this->add($source);

        $sourceLang = new Element\Select('fkLanguageSource');
        $sourceLang
            ->setOptions([
                'disable_inarray_validator' => true
            ])
        ;
        $this->add($sourceLang);

        $target = new Element\Text('target');
        $this->add($target);

        $targetLang = new Element\Select('fkLanguageTarget');
        $targetLang
            ->setOptions([
                'disable_inarray_validator' => true
            ])
        ;
        $this->add($targetLang);

        $fkGroup = new Element\Select('fkWordsGroup');
        $fkGroup
            ->setOptions([
                'disable_inarray_validator' => true
            ]);
        $this->add($fkGroup);

        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $sourceLangFilter = array(
            'name'       => 'fkLanguageSource',
            'required'   => true,
            'filters'    => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => 300,
                    ),
                ),
                array(
                    'name' => 'Digits',
                ),
            ),
        );
        $inputFilter->add($factory->createInput($sourceLangFilter));

        $targetLangFilter = array(
            'name'       => 'fkLanguageTarget',
            'required'   => true,
            'filters'    => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => 300,
                    ),
                ),
                array(
                    'name' => 'Digits',
                ),
            ),
        );
        $inputFilter->add($factory->createInput($targetLangFilter));

        $fkGroupFilter = array(
            'name'       => 'fkWordsGroup',
            'required'   => true,
            'filters'    => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => self::MAX_NUMERIC10,
                    ),
                ),
                array(
                    'name' => 'Digits',
                ),
            ),
        );
        $inputFilter->add($factory->createInput($fkGroupFilter));

        $sourceFilter = array(
            'name'       => 'source',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($sourceFilter));

        $targetFilter = array(
            'name'       => 'target',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($targetFilter));

        $this->setInputFilter($inputFilter);
    }
}