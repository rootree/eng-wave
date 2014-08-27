<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class WordsGroup extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('word');

        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\WordsGroup');
        $this->setHydrator($hydrator);

        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $this->add($id);

        $source = new Element\Text('title');
        $this->add($source);

        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $sourceFilter = array(
            'name'       => 'title',
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

        $this->setInputFilter($inputFilter);
    }
}