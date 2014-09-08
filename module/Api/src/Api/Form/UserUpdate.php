<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class UserUpdate extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('userUpdate');

        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\User');
        $this->setHydrator($hydrator);

        $this->setAttribute('method', 'post');

        $id = new Element\Hidden('id');
        $this->add($id);

        $name = new Element\Text('name');
        $this->add($name);

        $password = new Element\Text('password');
        $this->add($password);

        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();

        $nameFilter = array(
            'name'       => 'name',
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
                        'min'      => 2,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($nameFilter));

        $this->setInputFilter($inputFilter);
    }
}