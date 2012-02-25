<?php

namespace Fightmaster\Tests\Model\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Fightmaster\Model\Manager\DoctrineManagerAbstract;
use Fightmaster\Tests\Model\SimpleModel;
use Fightmaster\Tests\Model\SimpleAnotherModel;
use Fightmaster\Exception\InvalidArgumentException;

/**
 * Tests for \Fightmaster\Model\Manager\DoctrineManagerAbstract
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class DoctrineManagerAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * Unit Under Test (UUT)
     *
     * @var DoctrineManagerAbstract
     */
    protected $uut;

    /**
     * @var ObjectManager|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectRepository;

    /**
     * @var string
     */
    protected $fullClassName = 'Fightmaster\Tests\Model\SimpleModel';

    /**
     * @var string
     */
    protected $shortClassName = 'SimpleModel';

    /**
     * @test
     */
    public function getClass()
    {
        $this->assertEquals($this->fullClassName, $this->uut->getClass());
    }

    /**
     * @test
     */
    public function create()
    {
        $this->assertInstanceOf($this->fullClassName, $this->uut->create());
    }

    /**
     * @test
     * @depends getClass
     */
    public function saveWithoutFlush()
    {
        $this->objectManager->expects($this->never())->method('flush');
        $this->objectManager->expects($this->once())->method('persist');
        $simpleModel = new $this->fullClassName();
        $this->uut->save($simpleModel, false);
    }

    /**
     * @test
     * @depends getClass
     */
    public function saveWithFlush()
    {
        $this->objectManager->expects($this->once())->method('flush');
        $this->objectManager->expects($this->once())->method('persist');
        $simpleModel = new $this->fullClassName();
        $this->uut->save($simpleModel);
    }

    /**
     * @test
     * @depends getClass
     * @expectedException InvalidArgumentException
     */
    public function saveWithInvalidArguments()
    {
        $this->objectManager->expects($this->never())->method('flush');
        $this->objectManager->expects($this->never())->method('persist');
        $simpleModel = new SimpleAnotherModel();
        $this->uut->save($simpleModel);
    }

    /**
     * @test
     * @depends getClass
     */
    public function removeWithoutFlush()
    {
        $this->objectManager->expects($this->never())->method('flush');
        $this->objectManager->expects($this->once())->method('remove');
        $simpleModel = new $this->fullClassName();
        $this->uut->remove($simpleModel, false);
    }

    /**
     * @test
     * @depends getClass
     */
    public function removeWithFlush()
    {
        $this->objectManager->expects($this->once())->method('flush');
        $this->objectManager->expects($this->once())->method('remove');
        $simpleModel = new $this->fullClassName();
        $this->uut->remove($simpleModel);
    }

    /**
     * @test
     * @depends getClass
     * @expectedException InvalidArgumentException
     */
    public function removeWithInvalidArguments()
    {
        $this->objectManager->expects($this->never())->method('flush');
        $this->objectManager->expects($this->never())->method('remove');
        $simpleModel = new SimpleAnotherModel();
        $this->uut->remove($simpleModel);
    }

    /**
     * @test
     */
    public function find()
    {
        $id = 2;
        $return = new SimpleModel();
        $this->objectRepository->expects($this->once())->method('find')->with($id)->will($this->returnValue($return));
        $this->assertEquals($return, $this->uut->find($id));
    }

    /**
     * @test
     */
    public function findAll()
    {
        $return = array(new SimpleModel(), new SimpleAnotherModel());
        $this->objectRepository->expects($this->once())->method('findAll')->will($this->returnValue($return));
        $this->assertEquals($return, $this->uut->findAll());
    }

    /**
     * @test
     */
    public function findOneBy()
    {
        $return = new SimpleModel();
        $criteria = array('id' => 2);
        $this->objectRepository->expects($this->once())->method('findOneBy')->with($criteria)->will($this->returnValue($return));
        $this->assertEquals($return, $this->uut->findOneBy($criteria));
    }

    /**
     * @test
     */
    public function findBy()
    {
        $return = array(new SimpleModel());
        $criteria = array('id' => 2);
        $this->objectRepository->expects($this->once())->method('findBy')->with($criteria)->will($this->returnValue($return));
        $this->assertEquals($return, $this->uut->findBy($criteria));
    }

    /**
     * @test
     */
    public function flush()
    {
        $this->objectManager->expects($this->once())->method('flush');
        $this->uut->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $classMetadata = $this->getMockForAbstractClass('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $classMetadata->expects($this->once())->method('getName')->will($this->returnValue($this->fullClassName));
        $this->objectRepository = $this->getMockForAbstractClass('Doctrine\Common\Persistence\ObjectRepository');
        $this->objectManager = $this->getMockForAbstractClass('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager->expects($this->once())->method('getClassMetadata')->with($this->shortClassName)->will($this->returnValue($classMetadata));
        $this->objectManager->expects($this->once())->method('getRepository')->with($this->fullClassName)->will($this->returnValue($this->objectRepository));
        $this->uut = $this->getMockForAbstractClass(
            'Fightmaster\Model\Manager\DoctrineManagerAbstract',
            array($this->objectManager, $this->shortClassName));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->uut, $this->objectManager, $this->objectRepository);
    }
}
