<?php

namespace App\tests\Form;

use App\Entity\Bookmark;
use App\Form\BookmarkType;
use Symfony\Component\Form\Test\TypeTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BookmarkTypeTest extends TypeTestCase
{
    private $objectManager;
    
    private $validator;

    
    protected function setUp()
    {
        // mock any dependencies
        $this->objectManager = $this->createMock(ObjectManager::class);

        parent::setUp();
    }

    public function testSubmitValidData()
    {
        // Instead of creating a new instance, the one created in
        // getExtensions() will be used.
        $form = $this->factory->create(BookmarkType::class);
    
    }

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $this->validator = $this->getMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator),
        );
    }

}