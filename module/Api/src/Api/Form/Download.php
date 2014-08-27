<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class Download extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('download');

        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Download');
        $this->setHydrator($hydrator);

        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $this->add($id);

        $fkWordsGroup = new Element\Select('fkWordsGroup');
        $fkWordsGroup
            ->setOptions([
                'disable_inarray_validator' => true
            ])
        ;
        $this->add($fkWordsGroup);

        $fkStrategy = new Element\Select('fkStrategy');
        $fkStrategy
            ->setOptions([
                'disable_inarray_validator' => true
            ])
        ;
        $this->add($fkStrategy);

        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

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

        $fkStrategyFilter = array(
            'name'       => 'fkStrategy',
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
        $inputFilter->add($factory->createInput($fkStrategyFilter));

        $this->setInputFilter($inputFilter);
    }
}