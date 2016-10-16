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

use ArrayIterator;
use Closure;
use EmptyIterator;
use Traversable;
use Xloit\Collection\Exception;
use Xloit\Std\ArrayUtils;

/**
 * An {@link AbstractCollection} abstract class.
 *
 * @abstract
 * @package Xloit\Collection
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * The actual collection entries.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Constructor to prevent {@link Collection} from being loaded more than once.
     *
     * @param array $collection
     *
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function __construct($collection = null)
    {
        if (is_array($collection) || $collection instanceof Traversable) {
            $this->exchangeArray($collection);
        }
    }

    /**
     * Sets a whole new array replacement.
     *
     * @param array|Traversable $collection
     *
     * @return void
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function exchangeArray($collection)
    {
        if (!is_array($collection) && !($collection instanceof Traversable)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Expected argument "array" or "Traversable"; "%s" given',
                    gettype($collection) . (is_object($collection) ? '(' . get_class($collection) . ')' : '')
                )
            );
        }

        if ($collection instanceof Traversable) {
            $collection = ArrayUtils::iteratorToArray($collection);
        }

        foreach ($collection as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Sets the specified key name in this class with the specified element.
     *
     * @param mixed $index
     * @param mixed $element
     *
     * @return static
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function set($index, $element)
    {
        if (null === $index) {
            $this->collections[] = $element;
        } else {
            $this->collections[$index] = $element;
        }

        return $this;
    }

    /**
     * Appends the specified element to this {@link \Xloit\Std\Interop\Stack\AppendableInterface}. Depending on which
     * class implements the element may not be appended.
     *
     * @param mixed $element
     *
     * @return static
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function append($element)
    {
        return $this->add($element);
    }

    /**
     * Pushes the passed variable onto the end of the collection and returns the new number of elements in the
     * collection.
     *
     * @param mixed $element
     *
     * @return static
     * @throws \Xloit\Collection\Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function push($element)
    {
        return $this->add($element);
    }

    /**
     * Add new element to this with the specified element (*optional-operation). The
     * {@link \Xloit\Std\Interop\Stack\AddableInterface} implementation that support this operation may place
     * limitations on what elements may be added to this. In particular, some
     * {@link \Xloit\Std\Interop\Stack\AddableInterface} implementation will refuse to add null elements, and others
     * will impose restrictions on the type of elements that may be added. If an
     * {@link \Xloit\Std\Interop\Stack\AddableInterface} implementation refuses to add a particular element for any
     * reason other than that it already contains the element, it must throw an exception. This preserves the invariant
     * that an {@link \Xloit\Std\Interop\Stack\AddableInterface} implementation always contains the specified element
     * after this call returns.
     *
     * @param mixed $element
     *
     * @return static
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function add($element)
    {
        $this->set(null, $element);

        return $this;
    }

    /**
     * Prepend the specified element to this {@link \Xloit\Std\Interop\Stack\PrependableInterface}. Depending on
     * which class implements the element may not be appended.
     *
     * @param mixed $element
     *
     * @return static
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function prepend($element)
    {
        array_unshift($this->collections, $element);

        return $this;
    }

    /**
     * Prepends the passed item to the front of the collection and returns the new number of elements in the
     * collection.
     *
     * @param mixed $element
     *
     * @return void
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function unshift($element)
    {
        $this->prepend($element);
    }

    /**
     * Returns the element at the specified key name in this class.
     *
     * @param string|int $index
     *
     * @return mixed
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\OutOfBoundsException
     */
    public function get($index)
    {
        if (null === $index) {
            throw new Exception\InvalidArgumentException('The specified key name is null');
        }

        if (!$this->has($index)) {
            throw new Exception\OutOfBoundsException(sprintf('Index "%s" is out of bounds', $index));
        }

        return $this->collections[$index];
    }

    /**
     * Whether this contains the given name. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @param string|int $key
     *
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function has($key)
    {
        if (null === $key) {
            throw new Exception\InvalidArgumentException('The specified key name is null');
        }

        if (!is_scalar($key)) {
            throw new Exception\InvalidArgumentException('The specified key name must be a scalar');
        }

        return array_key_exists($key, $this->collections);
    }

    /**
     * Whether this contains the specified element. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function hasElement($element)
    {
        return in_array($element, $this->collections, true);
    }

    /**
     * Whether this contains the given key. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @see   CollectionInterface::has()
     *
     * @param string|int $key
     *
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function containsKey($key)
    {
        return $this->has($key);
    }

    /**
     * Whether this contains the specified element. More formally, returns true if and only if this contains at least
     * one element.
     *
     * @see   CollectionInterface::hasElement()
     *
     * @param mixed $element
     *
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function contains($element)
    {
        return $this->hasElement($element);
    }

    /**
     * Removes the specified element by key from this {@link RemovableInterface} implementations, if it is present
     * (*optional-operation). Returns true if this {@link RemovableInterface} implementations contained the specified
     * element (or equivalently, if this {@link RemovableInterface} implementations changed as a result of the call).
     *
     * @param string|int $key
     *
     * @return mixed The removed element
     * @throws Exception\InvalidArgumentException
     */
    public function remove($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        $removed = $this->collections[$key];

        unset($this->collections[$key]);

        return $removed;
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise.
     * @throws Exception\InvalidArgumentException
     */
    public function removeElement($element)
    {
        if (!$this->hasElement($element)) {
            return false;
        }

        $totalCollection = $this->count();
        $key             = $this->indexOf($element);

        unset($this->collections[$key]);

        return $this->count() !== $totalCollection;
    }

    /**
     * Shifts the first value of the collection off and returns it, shortening the collection by one element.
     *
     * @return  mixed
     */
    public function shift()
    {
        return array_shift($this->collections);
    }

    /**
     * Pops and returns the last value of the collection, shortening the collection by one element.
     *
     * @return  mixed
     */
    public function pop()
    {
        return array_pop($this->collections);
    }

    /**
     * Gets all keys/indices of this {@link KeysProviderInterface} implementation.
     *
     * @return array The keys/indices of this {@link KeysProviderInterface} implementation, in the order of the
     *               corresponding elements in this implementation.
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function getKeys()
    {
        return new static(array_keys($this->collections));
    }

    /**
     * Gets all values of this {@link ValuesProviderInterface} implementation.
     *
     * @return mixed The values of all elements in this {@link ValuesProviderInterface} implementation, in the order
     *               they appear in this implementation.
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function getValues()
    {
        return new static(array_values($this->collections));
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->collections);
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->collections);
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    public function key()
    {
        return key($this->collections);
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->collections);
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    public function next()
    {
        return next($this->collections);
    }

    /**
     * Indicates whether a offset exists.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset
     *
     * @return bool
     * @throws Exception\InvalidArgumentException
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset
     *
     * @return mixed
     * @throws Exception\InvalidArgumentException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\RuntimeException
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset.
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset
     *
     * @return void
     * @throws Exception\InvalidArgumentException
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param Closure $predicate The predicate
     *
     * @return bool TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(Closure $predicate)
    {
        foreach ($this->collections as $key => $element) {
            if ($predicate($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict, that means not only
     * the value but also the type must match. For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     *
     * @return int|string The key/index of the element or -1 if the element was not found.
     */
    public function indexOf($element)
    {
        return array_search($element, $this->collections, true);
    }

    /**
     * Sorts the collection using the specified comparator closure and returns TRUE on success and FALSE on
     * failure.
     *
     * @param Closure $comparator
     *
     * @return bool
     */
    public function sort(Closure $comparator)
    {
        return uasort($this->collections, $comparator);
    }

    /**
     * Chunks the collection into a collection containing $size sized collections.
     *
     * @param int     $size         Chunk size
     * @param boolean $preserveKeys When set to true keys will be preserved
     *
     * @return static
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \Xloit\Collection\Exception\InvalidArgumentException
     */
    public function chunk($size, $preserveKeys = null)
    {
        /** @var array $chunks */
        $chunks      = array_chunk($this->collections, $size, $preserveKeys);
        $collections = new static();

        foreach ($chunks as $chunk) {
            $collections->push(new static($chunk));
        }

        return $collections;
    }

    /**
     * Shuffles the items in the collection and returns TRUE on success and FALSE on failure.
     *
     * @return  bool
     */
    public function shuffle()
    {
        return shuffle($this->collections);
    }

    /**
     * Applies the given function to each element in the collection and returns a new collection with the elements
     * returned by the function.
     *
     * @param Closure $mapper
     *
     * @return static A {@link MappableInterface} implementation with the new results of the map operation.
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function map(Closure $mapper)
    {
        return new static(array_map($mapper, $this->collections));
    }

    /**
     * Returns all the elements of this {@link FilterableInterface} implementation that satisfy the predicate.
     * The order of the elements is preserved.
     *
     * @param Closure $predicate The predicate used for filtering.
     *
     * @return static A {@link FilterableInterface} implementation with the results of the filter operation.
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function filter(Closure $predicate)
    {
        return new static(array_filter($this->collections, $predicate));
    }

    /**
     * Tests whether the given predicate holds for all elements of this collection.
     *
     * @param Closure $predicate The predicate on which to iterated.
     *
     * @return bool TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     *              predicate returned FALSE.
     */
    public function forAll(Closure $predicate)
    {
        foreach ($this->collections as $key => $element) {
            if (!$predicate($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns all the elements of this {@link PartitioningInterface} implementation that satisfy in two collections
     * according to a predicate. Keys are preserved in the resulting collections.
     *
     * @param Closure $predicate The predicate on which to partition.
     *
     * @return static[] An array with two elements. The first element contains the collection of elements where the
     * @throws Exception\InvalidArgumentException predicate returned TRUE, the second element contains the
     *                                            collection of elements where the predicate returned FALSE.
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function partition(Closure $predicate)
    {
        $matches   = [];
        $noMatches = [];

        foreach ($this->collections as $key => $element) {
            if ($predicate($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [
            new static($matches),
            new static($noMatches)
        ];
    }

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection. Keys have to be
     * preserved by this method. Calling this method will only return the selected slice and NOT change the
     * elements contained in the collection slice is called on.
     *
     * @param int      $offset The offset to start from.
     * @param int|null $length The maximum number of elements to return, or null for no limit.
     *
     * @return static A collection of the sliced elements.
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->collections, $offset, $length, true));
    }

    /**
     * Count elements of an object.
     *
     * @link  http://php.net/manual/en/countable.count.php
     *
     * @return int
     */
    public function count()
    {
        return count($this->collections);
    }

    /**
     * Retrieve an external iterator.
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return !$this->isEmpty() ? new ArrayIterator($this->collections) : new EmptyIterator();
    }

    /**
     * Returns whether the object is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->collections) === 0;
    }

    /**
     * Clear the collections data.
     *
     * @return static
     */
    public function clear()
    {
        $this->collections = [];

        return $this;
    }

    /**
     * Merge another Collection with this one.
     *
     * For duplicate keys, the following will be performed:
     * - Nested Configs will be recursively merged.
     * - Items in $merge with INTEGER keys will be appended.
     * - Items in $merge with STRING keys will overwrite current values.
     *
     * @param array|Traversable $collection
     *
     * @return static
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \Xloit\Collection\Exception\InvalidArgumentException
     */
    public function merge($collection)
    {
        if (!is_array($collection) && !($collection instanceof Traversable)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Expected argument "array" or "Traversable"; "%s" given',
                    gettype($collection) . (is_object($collection) ? '(' . get_class($collection) . ')' : '')
                )
            );
        }

        foreach ($collection as $key => $value) {
            if ($this->has($key)) {
                if (is_int($key)) {
                    $this->append($value);
                } else {
                    if ($value instanceof CollectionInterface) {
                        $value = $value->toArray();

                        if ($this->collections[$key] instanceof CollectionInterface) {
                            $value = $this->collections[$key]->merge($value);
                        } else {
                            $value = new static($value);
                        }
                    }

                    $this->set($key, $value);
                }
            } else {
                if ($value instanceof CollectionInterface) {
                    $value = new static($value->toArray());
                }

                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * String representation of object.
     *
     * @link  http://php.net/manual/en/serializable.serialize.php
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * Constructs the object.
     *
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized
     *
     * @return void
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function unserialize($serialized)
    {
        $this->exchangeArray(unserialize($serialized));
    }

    /**
     * Get the array representation of this object.
     *
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($recursive = true)
    {
        $results     = [];
        $collections = $this->collections;

        if ($recursive) {
            foreach ($collections as $key => $value) {
                if ($value instanceof CollectionInterface) {
                    /** @noinspection PhpMethodParametersCountMismatchInspection */
                    $results[$key] = $value->toArray($recursive);
                } else {
                    $results[$key] = $value;
                }
            }
        } else {
            foreach ($collections as $key => $value) {
                $results[$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Creates and returns a copy of this object. The precise meaning of "copy" may depend on the class of the object.
     *
     * @return static
     */
    public function __clone()
    {
        $results = [];

        foreach ($this->collections as $key => $value) {
            if ($value instanceof CollectionInterface) {
                $value = clone $value;
            }

            $results[$key] = $value;
        }

        $this->collections = $results;
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return static::class . '@' . spl_object_hash($this);
    }
}
