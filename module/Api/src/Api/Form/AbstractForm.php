<?php
namespace Api\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class AbstractForm extends Form
{
    const MAX_NUMERIC10 = 99999999;
    const MAX_WORDS     = 250;
    const MAX_FEEDBACK     = 5250;
}