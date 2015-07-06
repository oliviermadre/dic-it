<?php

namespace Pyrite\DI;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use Pyrite\DI\BuildChain\Chainable;
use Pyrite\DI\BuildSequence\BuildSequence;
use Pyrite\DI\ReferenceResolver\ReferenceResolverDispatcher;

interface Container extends ContainerInterface
{
    /**
     * Finds a parameter entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function getParameter($id);

    /**
     * Returns true if the container can return a parameter entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function hasParameter($id);

    /**
     * @param Chainable $chain
     */
    public function setBuildSequence(BuildSequence $sequence);

    /**
     * @return ReferenceResolverDispatcher
     */
    public function getReferenceResolverDispatcher();

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @param $id
     * @param $object
     * @return Container
     */
    public function bind($id, $object);

    /**
     * @param $key
     * @param $value
     * @return Container
     */
    public function bindParameter($key, $value);
}