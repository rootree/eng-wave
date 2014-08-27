<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class Strategy extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('strategy');

        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Strategy');
        $this->setHydrator($hydrator);

        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $this->add($id);

        $title = new Element\Text('title');
        $this->add($title);

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