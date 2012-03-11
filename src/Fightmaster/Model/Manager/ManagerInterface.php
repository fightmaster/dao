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

use \object;

/**
 * Interface to be implemented by managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to object should happen through this interface.
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
interface ManagerInterface
{
    /**
     * Returns fully qualified class name of the object.
     *
     * @abstract
     * @return string
     */
    public function getClass();

    /**
     * Creates an empty object instance.
     *
     * @abstract
     * @return Object
     */
    public function create();

    /**
     * Saves the object
     *
     * @abstract
     * @param object $object
     * @param bool $flush
     */
    public function save($object, $flush = true);

    /**
     * Removes the object
     *
     * @abstract
     * @param object $object
     * @param bool $flush
     */
    public function remove($object, $flush = true);

    /**
     * Finds an object by its primary key / identifier.
     *
     * @abstract
     * @param int|string $id The identifier.
     * @return object The object.
     */
    public function find($id);

    /**
     * Finds all objects in the repository.
     *
     * @return object[] The objects.
     */
    public function findAll();

    /**
     * Finds a single object by a set of criteria.
     *
     * @abstract
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria);

    /**
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @abstract
     * @throws UnexpectedValueException
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return object[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     *
     * @abstract
     */
    public function flush();
}
