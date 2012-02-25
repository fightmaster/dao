<?php


namespace Fightmaster\Service;

use Fightmaster\Exception\InvalidArgumentException;
use Fightmaster\Model\Manager\ManagerInterface;

/**
 * Service implementation which can be used as base class for your concrete service
 * This is the so-called "service layer"/"business logic layer" of your application
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class Service implements ServiceInterface
{
    protected $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function create()
    {
        return $this->manager->create();
    }

    public function save($object)
    {
        if ($this->isExpectedObject($object)) {
            $this->manager->save($object);
        }

    }

    public function remove($object)
    {
        if ($this->isExpectedObject($object)) {
            $this->manager->remove($object);
        }
    }

    /**
     * Checks entity
     *
     * @param $object
     * @return bool
     * @throws InvalidArgumentException
     */
    private function isExpectedObject($object)
    {
        $className = $this->manager->getClass();
        if (!$object instanceof $className) {
            throw new InvalidArgumentException();
        }

        return true;
    }
}
