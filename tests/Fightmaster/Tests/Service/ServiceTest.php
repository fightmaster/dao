<?php

namespace Fightmaster\Tests\Service;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Fightmaster\Model\Manager\ManagerInterface;
use Fightmaster\Model\Manager\DoctrineManagerAbstract;
use Fightmaster\Service\Service;
use Fightmaster\Tests\Model\SimpleModel;
use Fightmaster\Tests\Model\SimpleAnotherModel;
use Fightmaster\Exception\InvalidArgumentException;

/**
 * Tests for \Fightmaster\Service\Service
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Unit Under Test (UUT)
     *
     * @var Service
     */
    protected $uut;

    /**
     * @var ManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $manager;

    /**
     * @test
     */
    public function create()
    {
        $object = new SimpleModel();
        $this->manager->expects($this->once())->method('create')->will($this->returnValue($object));
        $this->assertInstanceOf(get_class($object), $this->uut->create());
    }

    /**
     * @test
     */
    public function save()
    {
        $object = new SimpleModel();
        $className = get_class($object);
        $this->manager->expects($this->once())->method('getClass')->will($this->returnValue($className));
        $this->manager->expects($this->once())->method('save');
        $this->uut->save($object);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function saveWithInvalidArguments()
    {
        $object = new SimpleModel();
        $anotherObject = new SimpleAnotherModel();
        $className = get_class($anotherObject);
        $this->manager->expects($this->once())->method('getClass')->will($this->returnValue($className));
        $this->manager->expects($this->never())->method('save');
        $this->uut->save($object);
    }

    /**
     * @test
     */
    public function remove()
    {
        $object = new SimpleModel();
        $className = get_class($object);
        $this->manager->expects($this->once())->method('getClass')->will($this->returnValue($className));
        $this->manager->expects($this->once())->method('remove');
        $this->uut->remove($object);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function removeWithInvalidArguments()
    {
        $object = new SimpleModel();
        $anotherObject = new SimpleAnotherModel();
        $className = get_class($anotherObject);
        $this->manager->expects($this->once())->method('getClass')->will($this->returnValue($className));
        $this->manager->expects($this->never())->method('remove');
        $this->uut->remove($object);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manager = $this->getMockForAbstractClass('Fightmaster\Model\Manager\ManagerInterface');
        $this->uut = $this->getMockForAbstractClass('Fightmaster\Service\Service', array($this->manager));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->uut, $this->manager);
    }
}
