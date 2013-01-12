<?php

/**
 * This file is part of the Fightmaster/dao library.
 *
 * (c) Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fightmaster\Model\Manager;

use UnexpectedValueException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Fightmaster\Model\Manager\Exception\InvalidArgumentException;

/**
 * Doctrine Manager implementation which can be used as base class for your concrete manager (ORM or ODM, or another)
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class DoctrineManager implements ManagerInterface
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * @var string
     */
    private $class;

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->class = $this->objectManager->getClassMetadata($class)->getName();
        $this->objectRepository = $this->objectManager->getRepository($this->getClass());
    }

    /**
     * Returns fully qualified class name of the object.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Creates an empty object instance.
     *
     * @return object
     */
    public function create()
    {
        $class = $this->getClass();

        return new $class;
    }

    /**
     * Saves the object
     *
     * @param Object $object
     * @param bool $flush
     * @throws InvalidArgumentException
     */
    public function save($object, $flush = true)
    {
        $this->isExpectedObject($object);
        $this->objectManager->persist($object);
        if ($flush) {
            $this->flush();
        }
    }

    /**
     * Removes the object
     *
     * @param Object $object
     * @param bool $flush
     * @throws InvalidArgumentException
     */
    public function remove($object, $flush = true)
    {
        $this->isExpectedObject($object);
        $this->objectManager->remove($object);
        if ($flush) {
            $this->flush();
        }
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param $id
     * @return object
     */
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }

    /**
     * Finds all objects in the repository.
     *
     * @return mixed The objects.
     */
    public function findAll()
    {
        return $this->objectRepository->findAll();
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->objectRepository->findOneBy($criteria);
    }

    /**
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return Object[]
     * @throws UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->objectRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     */
    public function flush()
    {
        $this->objectManager->flush();
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
        $className = $this->getClass();
        if (!is_object($object) || !$object instanceof $className) {
            throw new InvalidArgumentException();
        }

        return true;
    }
}
