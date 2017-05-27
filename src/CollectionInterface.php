<?php
/**
 * This source file is part of Xloit project.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * <http://www.opensource.org/licenses/mit-license.php>
 * If you did not receive a copy of the license and are unable to obtain it through the world-wide-web,
 * please send an email to <license@xloit.com> so we can send you a copy immediately.
 *
 * @license   MIT
 * @link      http://xloit.com
 * @copyright Copyright (c) 2016, Xloit. All rights reserved.
 */

namespace Xloit\Collection;

use ArrayAccess;
use Closure;
use Countable;
use IteratorAggregate;
use Serializable;
use Xloit\Std\Interop\Action;
use Xloit\Std\Interop\Container;
use Xloit\Std\Interop\Indicator;
use Xloit\Std\Interop\Stack;

/**
 * A {@link CollectionInterface} interface.
 *
 * @package Xloit\Collection
 */
interface CollectionInterface
    extends ArrayAccess,
            Stack\AddableInterface,
            Stack\AppendableInterface,
            Stack\PrependableInterface,
            Stack\RemovableInterface,

            Container\GetterSetterInterface,
            Container\ContainableInterface,
            Container\ElementContainableInterface,
            Container\RemovableInterface,

            Stack\KeysProviderInterface,
            Stack\ValuesProviderInterface,
            Indicator\EmptyCheckerInterface,

            Stack\ExistenceInterface,
            Stack\IterationInterface,
            Stack\FilterableInterface,
            Stack\MappableInterface,
            Stack\IndexedInterface,
            Stack\SlicingInterface,
            Stack\PartitioningInterface,

            Action\ClearableInterface,
            Action\CloneableInterface,
            Action\ToArrayInterface,
            Action\ToStringInterface,
            Countable,
            IteratorAggregate,
            Serializable

{
    /**
     * Prepends the passed item to the front of the collection and returns the new number of elements in the
     * collection.
     *
     * @param mixed $element
     *
     * @return void
     */
    public function unshift($element);

    /**
     * Whether this contains the specified element. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @see   CollectionInterface::hasElement()
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element);

    /**
     * Whether this contains the given key. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @see   CollectionInterface::has()
     *
     * @param string|int $key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * Shifts the first value of the collection off and returns it, shortening the collection by one element.
     *
     * @return  mixed
     */
    public function shift();

    /**
     * Pops and returns the last value of the collection, shortening the collection by one element.
     *
     * @return  mixed
     */
    public function pop();

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    public function first();

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    public function last();

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    public function key();

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    public function current();

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    public function next();

    /**
     * Sorts the collection using the specified comparator closure and returns TRUE on success and FALSE on
     * failure.
     *
     * @param Closure $comparator
     *
     * @return bool
     */
    public function sort(Closure $comparator);

    /**
     * Chunks the collection into a collection containing $size sized collections.
     *
     * @param int  $size         Chunk size
     * @param bool $preserveKeys When set to true keys will be preserved
     *
     * @return $this
     */
    public function chunk($size, $preserveKeys = null);

    /**
     * Shuffles the items in the collection and returns TRUE on success and FALSE on failure.
     *
     * @return  bool
     */
    public function shuffle();

    /**
     * Merge another Collection with this one.
     *
     * For duplicate keys, the following will be performed:
     * - Nested Configs will be recursively merged.
     * - Items in $merge with INTEGER keys will be appended.
     * - Items in $merge with STRING keys will overwrite current values.
     *
     * @param array|\Traversable $collection
     *
     * @return $this
     */
    public function merge($collection);
}
