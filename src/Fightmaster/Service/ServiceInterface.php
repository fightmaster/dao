<?php


namespace Fightmaster\Service;

/**
 * Interface to be implemented by services. This is the so-called "service layer"/"business logic layer" of your application
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
interface ServiceInterface
{
    /**
     * Creates an empty object instance.
     *
     * @abstract
     */
    public function create();

    /**
     * Saves the object
     *
     * @abstract
     * @param $object
     */
    public function save($object);

    /**
     * Removes the object
     *
     * @abstract
     * @param $object
     */
    public function remove($object);
}
