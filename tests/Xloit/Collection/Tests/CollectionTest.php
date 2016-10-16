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

namespace Xloit\Collection\Tests;

use ArrayIterator;
use EmptyIterator;
use Xloit\Collection\Collection;

/**
 * Test class for {@link CollectionTest}
 *
 * @package Xloit\Collection\Tests
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testConstructor($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(count($elements), count($collection));
    }

    /**
     * Sets a whole new array replacement
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testExchangeArray($elements)
    {
        $collection = new Collection();

        $collection->exchangeArray($elements);

        $this->assertSame(count($elements), count($collection));
    }

    /**
     * Sets a whole new array replacement with traversable
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testExchangeArrayTraversable($elements)
    {
        $collection = new Collection();

        $collection->exchangeArray(new Collection($elements));

        $this->assertSame(count($elements), count($collection));
    }

    /**
     * Sets a whole new array replacement with null as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "NULL" given
     */
    public function testExchangeArrayInputNull()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->exchangeArray(null);
    }

    /**
     * Sets a whole new array replacement with integer as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "integer" given
     */
    public function testExchangeArrayInputInteger()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->exchangeArray(1);
    }

    /**
     * Sets a whole new array replacement with string as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "string" given
     */
    public function testExchangeArrayInputString()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->exchangeArray('string');
    }

    /**
     * Sets a whole new array replacement with object as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "object(stdClass)" given
     */
    public function testExchangeArrayInputObject()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->exchangeArray(new \stdClass());
    }

    /**
     * Sets the specified key name in this class with the specified element.
     */
    public function testInsertElementAsAssociative()
    {
        $collection = new Collection();

        $collection->set('index', 'value');

        $this->assertTrue(isset($collection['index']));
        $this->assertSame('value', $collection['index']);

        $collection['offsetSet'] = 'offsetValue';

        $this->assertTrue(isset($collection['offsetSet']));
        $this->assertSame('offsetValue', $collection['offsetSet']);
    }

    /**
     * Sets the specified element as an indexed array.
     */
    public function testInsertElementAsIndexed()
    {
        $collection = new Collection();

        $collection->set(null, 'value');

        $this->assertTrue(isset($collection[0]));
        $this->assertSame('value', $collection[0]);
    }

    /**
     * Sets the specified array element.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testInsertElementFromArray($elements)
    {
        $collection = new Collection();

        $collection->set('index', $elements);

        $this->assertTrue(isset($collection['index']));
        $this->assertSame($elements, $collection['index']);
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testToArrayRepresentation($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testToArrayRepresentationRecursive($elements)
    {
        $deep = new Collection($elements);
        $collectionElements = [
            'deep' => $deep
        ];
        $collection = new Collection($collectionElements);

        $this->assertInstanceOf(Collection::class, $collection->get('deep'));
        $this->assertSame($deep, $collection->get('deep'));
        $this->assertNotSame($collectionElements, $collection->toArray());
        $this->assertNotSame($deep, $collection->toArray()['deep']);
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testToArrayRepresentationNotRecursive($elements)
    {
        $deep = new Collection($elements);
        $collectionElements = [
            'deep' => $deep
        ];
        $collection = new Collection($collectionElements);

        $this->assertSame($collectionElements, $collection->toArray(false));
        $this->assertSame($deep, $collection->toArray(false)['deep']);
    }

    public function testToStringRepresentation()
    {
        $collection = new Collection(['test_string']);
        $value      = (string) $collection;

        $this->assertTrue(is_string($value));
        $this->assertInternalType('integer', strpos($value, Collection::class));
    }

    /**
     * Returns the first element in the collection and returns this element.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testFirst($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(reset($elements), $collection->first());
    }

    /**
     * Returns the last element in the collection and returns this element.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testLast($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(end($elements), $collection->last());
    }

    /**
     * Gets all keys/indices from collection.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testCollectionKeys($elements)
    {
        $collection = new Collection($elements);

        $this->assertNotSame(array_keys($elements), $collection->getKeys());
        $this->assertInstanceOf(Collection::class, $collection->getKeys());
        $this->assertSame(count(array_keys($elements)), count($collection->getKeys()));
    }

    /**
     * Gets all values from collection.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testCollectionValues($elements)
    {
        $collection = new Collection($elements);

        $this->assertNotSame(array_values($elements), $collection->getValues());
        $this->assertInstanceOf(Collection::class, $collection->getValues());
        $this->assertSame(count(array_values($elements)), count($collection->getValues()));
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testKey($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(key($elements), $collection->key());

        next($elements);
        $collection->next();

        $this->assertSame(key($elements), $collection->key());
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testNext($elements)
    {
        $collection = new Collection($elements);

        while (true) {
            $collectionNext = $collection->next();
            $arrayNext      = next($elements);

            if (!$collectionNext || !$arrayNext) {
                break;
            }

            $this->assertSame(
                $arrayNext, $collectionNext, 'Returned value of Collection::next() and next() not match'
            );
            $this->assertSame(key($elements), $collection->key(), 'Keys not match');
            $this->assertSame(current($elements), $collection->current(), 'Current values not match');
        }
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testCurrent($elements)
    {
        $collection = new Collection($elements);

        $this->assertSame(current($elements), $collection->current());

        next($elements);
        $collection->next();

        $this->assertSame(current($elements), $collection->current());
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testCount($elements)
    {
        $collection = new Collection($elements);

        $this->assertCount(count($elements), $collection);
    }

    /**
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testIterator($elements)
    {
        $collection = new Collection($elements);
        $iterator   = $collection->getIterator();
        $iterations = 0;

        $this->assertInstanceOf(ArrayIterator::class, $iterator);

        foreach ($iterator as $key => $item) {
            $this->assertSame($elements[$key], $item, sprintf('Item "%s" not match', $key));

            $iterations++;
        }

        $this->assertCount($iterations, $elements, 'Number of iterations not match');
    }

    public function testEmptyElements()
    {
        $collection = new Collection();

        $this->assertInstanceOf(EmptyIterator::class, $collection->getIterator());
    }

    public function testEmpty()
    {
        $collection = new Collection();
        $this->assertTrue($collection->isEmpty(), 'Empty collection');

        $collection->add(1);
        $this->assertFalse($collection->isEmpty(), 'Not empty collection');
    }

    /**
     * Removes the specified element by key from the collection.
     */
    public function testRemove()
    {
        $elements   = [
            1,
            'A' => 'a',
            2,
            'B' => 'b',
            3
        ];
        $collection = new Collection($elements);

        $this->assertSame(1, $collection->remove(0));
        unset($elements[0]);

        $this->assertSame(2, $collection->remove(1));
        unset($elements[1]);

        $this->assertSame('a', $collection->remove('A'));
        unset($elements['A']);

        $this->assertNull($collection->remove('non-existent'));
        unset($elements['non-existent']);

        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * Removes the null element by key from the collection.
     */
    public function testRemoveNullValuesByKey()
    {
        $collection = new Collection([null]);

        $collection->remove(0);

        $this->assertTrue($collection->isEmpty());
    }

    public function testRemoveElement()
    {
        $object     = new \stdClass;
        $elements   = [
            1,
            'A'      => 'a',
            2,
            'B'      => 'b',
            3,
            'object' => $object
        ];
        $collection = new Collection($elements);

        $this->assertTrue($collection->removeElement(1));
        unset($elements[0]);

        $this->assertTrue($collection->removeElement(2));
        unset($elements[1]);

        $this->assertTrue($collection->removeElement('a'));
        unset($elements['A']);

        $this->assertTrue($collection->removeElement($object));
        unset($elements['object']);

        $this->assertFalse($collection->removeElement('non-existent'));
        unset($elements['non-existent']);

        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * Removes the null element from the collection.
     */
    public function testRemoveNullElement()
    {
        $collection = new Collection([null]);

        $this->assertTrue($collection->removeElement(null));
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * Shifts the first value of the collection off and returns it, shortening the collection by one element.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testShift($elements)
    {
        $collection = new Collection($elements);

        $elementShift    = array_shift($elements);
        $collectionShift = $collection->shift();

        $this->assertCount(count($elements), $collection);
        $this->assertSame($elementShift, $collectionShift);
    }

    /**
     * Pops and returns the last value of the collection, shortening the collection by one element.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testPop($elements)
    {
        $collection = new Collection($elements);

        $elementPop    = array_pop($elements);
        $collectionPop = $collection->pop();

        $this->assertCount(count($elements), $collection);
        $this->assertSame($elementPop, $collectionPop);
    }

    /**
     * Clear the collections data
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testClear($elements)
    {
        $collection = new Collection($elements);

        $collection->clear();

        $this->assertEquals(true, $collection->isEmpty());
        $this->assertCount(0, $collection);
        $this->assertNotSame(count($elements), count($collection));
    }

    /**
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The specified key name is null
     */
    public function testRetrieveNullIndex()
    {
        $collection = new Collection();

        $collection->get(null);
    }

    /**
     * @expectedException \Xloit\Collection\Exception\OutOfBoundsException
     * @expectedExceptionMessage Index "0" is out of bounds
     */
    public function testRetrieveInvalidIndex()
    {
        $collection = new Collection();

        $collection->get(0);
    }

    /**
     * Whether this contains the given key. More formally, returns true if and only if this contains at least
     * one element.
     */
    public function testContainsKey()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3
        ];
        $collection = new Collection($elements);

        $this->assertTrue($collection->containsKey(0), 'Contains index 0');
        $this->assertTrue($collection->containsKey('A'), 'Contains key "A"');
        $this->assertTrue($collection->containsKey('null'), 'Contains key "null", with value null');
        $this->assertFalse($collection->containsKey('non-existent'), 'Doesn\'t contain key');
    }

    /**
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The specified key name is null
     */
    public function testContainsKeyInvalidArgumentsNull()
    {
        $collection = new Collection();

        $collection->containsKey(null);
    }

    /**
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The specified key name must be a scalar
     */
    public function testContainsKeyInvalidArgumentsArray()
    {
        $collection = new Collection();

        $collection->containsKey([]);
    }

    /**
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The specified key name must be a scalar
     */
    public function testContainsKeyInvalidArgumentsObject()
    {
        $collection = new Collection();

        $collection->containsKey(new Collection);
    }

    /**
     * Whether this contains the specified element. More formally, returns true if and only if this contains at least
     * one element.
     */
    public function testContains()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $this->assertTrue($collection->contains(0), 'Contains Zero');
        $this->assertTrue($collection->contains('a'), 'Contains "a"');
        $this->assertTrue($collection->contains(null), 'Contains Null');
        $this->assertFalse($collection->contains('non-existent'), 'Doesn\'t contain an element');
    }

    /**
     * Returns the element at the specified key name in this collection.
     */
    public function testGet()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $this->assertSame(2, $collection->get(1), 'Get element by index');
        $this->assertSame('a', $collection->get('A'), 'Get element by name');
    }

    /**
     * Returns the element at the specified non existent key name in the collection.
     *
     * @expectedException \Xloit\Collection\Exception\OutOfBoundsException
     * @expectedExceptionMessage Index "non-existent" is out of bounds
     */
    public function testGetInvalidIndex()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $collection->get('non-existent');
    }

    public function testIssetAndUnset()
    {
        $collection = new Collection();

        $this->assertFalse(isset($collection[0]));

        $collection->add('test_isset_unset');
        $this->assertTrue(isset($collection[0]));

        unset($collection[0]);
        $this->assertFalse(isset($collection[0]));
    }

    /**
     * Appends the specified element to the collection.
     */
    public function testAppend()
    {
        $collection = new Collection();
        $elements = [1, 2, 3, 4, 5];

        foreach ($elements as $key => $value) {
            $collection->append($value);
        }

        $this->assertNotCount(0, $collection);
        $this->assertCount(count($elements), $collection);
        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * Push the specified element to the collection.
     */
    public function testPush()
    {
        $collection = new Collection();
        $elements = [1, 2, 3, 4, 5];

        foreach ($elements as $key => $value) {
            $collection->push($value);
        }

        $this->assertNotCount(0, $collection);
        $this->assertCount(count($elements), $collection);
        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * Add the specified element to the collection.
     */
    public function testAdd()
    {
        $collection = new Collection();
        $elements = [1, 2, 3, 4, 5];

        foreach ($elements as $key => $value) {
            $collection->add($value);
        }

        $this->assertNotCount(0, $collection);
        $this->assertCount(count($elements), $collection);
        $this->assertSame($elements, $collection->toArray());
    }

    /**
     * Prepend the specified element to the collection.
     */
    public function testPrepend()
    {
        $collection = new Collection();
        $elements = [1, 2, 3, 4, 5];

        foreach ($elements as $key => $value) {
            $collection->prepend($value);
        }

        $this->assertNotCount(0, $collection);
        $this->assertCount(count($elements), $collection);
        $this->assertNotSame($elements, $collection->toArray());
        $this->assertSame(array_reverse($elements), $collection->toArray());
    }

    /**
     * Prepends the passed item to the front of the collection
     * and returns the new number of elements in the collection.
     */
    public function testUnshift()
    {
        $collection = new Collection();
        $elements = [1, 2, 3, 4, 5];

        foreach ($elements as $key => $value) {
            $collection->unshift($value);
        }

        $this->assertNotCount(0, $collection);
        $this->assertCount(count($elements), $collection);
        $this->assertNotSame($elements, $collection->toArray());
        $this->assertSame(array_reverse($elements), $collection->toArray());
    }

    /**
     * Shuffles the items in the collection.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testShuffle($elements)
    {
        $collection = new Collection($elements);

        $this->assertNotEquals($elements, $collection->shuffle());
        $this->assertEquals(count($elements), count($collection));
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     */
    public function testExists()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $this->assertTrue(
            $collection->exists(
                function($key, $element) {
                    return $key === 'A' && $element === 'a';
                }
            ), 'Element exists'
        );

        $this->assertFalse(
            $collection->exists(
                function($key, $element) {
                    return $key === 'non-existent' && $element === 'non-existent';
                }
            ), 'Element not exists'
        );
    }

    /**
     * Gets the index/key of a given element.
     */
    public function testIndexOf()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $this->assertSame(array_search(2, $elements, true), $collection->indexOf(2), 'Index of 2');
        $this->assertSame(array_search(null, $elements, true), $collection->indexOf(null), 'Index of null');
        $this->assertSame(
            array_search('non-existent', $elements, true),
            $collection->indexOf('non-existent'),
            'Index of non existent'
        );
    }

    /**
     * Sorts the collection using the specified comparator closure
     * and returns TRUE on success and FALSE on failure.
     */
    public function testSort()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $expected   = [
            'A'    => 'a',
            'null' => null,
            'zero' => 0,
            1,
            2,
            3
        ];
        $collection = new Collection($elements);

        $this->assertTrue(
            $collection->sort(
                function($value, $key) {
                    if ($key === 'A') {
                        return 0;
                    } elseif ($key === 'null') {
                        return 1;
                    } elseif ($key === 'zero') {
                        return 2;
                    }

                    return is_int($value) ? ($value + 2) : $key + 2;
                }
            )
        );
        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * Chunks the collection into a collection containing $size sized collections.
     */
    public function testChunk()
    {   
        $elements = ['a', 'b', 'c', 'd', 'e'];
        $collection = new Collection($elements);
        $chunks = $collection->chunk(2);

        $this->assertInstanceOf(Collection::class, $chunks);
        $this->assertCount(3, $chunks);
        $this->assertSame([
            ['a', 'b'],
            ['c', 'd'],
            ['e']
        ], $chunks->toArray());

        /** @var \Traversable $chunks */
        foreach ($chunks as $chunk) {
            $this->assertInstanceOf(Collection::class, $chunk);
        }
    }

    /**
     * Chunks the collection into a collection containing $size sized collections.
     */
    public function testChunkPreserveKeys()
    {   
        $elements = ['a', 'b', 'c', 'd', 'e'];
        $collection = new Collection($elements);
        $chunks = $collection->chunk(2, true);

        $this->assertInstanceOf(Collection::class, $chunks);
        $this->assertCount(3, $chunks);
        $this->assertSame([
            [
                0 => 'a',
                1 => 'b'
            ],
            [
                2 => 'c',
                3 => 'd'
            ],
            [
                4 => 'e'
            ]
        ], $chunks->toArray());
    }

    /**
     * Applies the given function to each element in the collection and returns a new collection with the elements
     * returned by the function.
     */
    public function testMap()
    {
        $elements   = [
            0,
            1,
            2,
            3
        ];
        $collection = new Collection($elements);

        $results = $collection->map(
            function($value) {
                return $value * 2;
            }
        );

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertEquals(
            [
                0,
                2,
                4,
                6
            ], $results->toArray()
        );
    }

    /**
     * Returns all the elements of the collection that satisfy the predicate.
     */
    public function testFilter()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        $results = $collection->filter(
            function($value) {
                return is_int($value);
            }
        );

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertEquals(
            [
                1,
                2,
                3,
                'zero' => 0
            ], $results->toArray()
        );
    }

    /**
     * Tests whether the given predicate holds for all elements of this collection.
     */
    public function testForAll()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        /** @noinspection PhpUnusedParameterInspection */
        $this->assertTrue(
            $collection->forAll(
                function($key, $value) {
                    return is_string($value) || is_int($value) || $value === null;
                }
            )
        );
        /** @noinspection PhpUnusedParameterInspection */
        $this->assertFalse(
            $collection->forAll(
                function($key, $value) {
                    return is_string($value);
                }
            )
        );
    }

    /**
     * Returns all the elements of the collection that satisfy in two collections
     * according to a predicate. Keys are preserved in the resulting collections.
     */
    public function testPartition()
    {
        $elements   = [
            1,
            'A'    => 'a',
            2,
            'null' => null,
            3,
            'zero' => 0
        ];
        $collection = new Collection($elements);

        /** @noinspection PhpUnusedParameterInspection */
        $partition = $collection->partition(
            function($key, $value) {
                return $value === null;
            }
        );
        $this->assertEquals(['null' => null], $partition[0]->toArray());
        $this->assertEquals(
            [
                1,
                'A'    => 'a',
                2,
                3,
                'zero' => 0
            ], $partition[1]->toArray()
        );
    }

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     */
    public function testSlice()
    {
        $elements   = [
            'one',
            'two',
            'three'
        ];
        $collection = new Collection($elements);

        $slice = $collection->slice(0, 1);
        $this->assertInstanceOf(Collection::class, $slice);
        $this->assertEquals(['one'], $slice->toArray());

        $slice = $collection->slice(1);
        $this->assertEquals(
            [
                1 => 'two',
                2 => 'three'
            ], $slice->toArray()
        );

        $slice = $collection->slice(1, 1);
        $this->assertEquals([1 => 'two'], $slice->toArray());
    }

    /**
     * Merge another Collection with other collection.
     */
    public function testMerge()
    {   
        $elements = ['a', 'b'];
        $mergeElements = ['c', 'd', 'e'];
        $collection = new Collection($elements);
        
        $collection->merge($mergeElements);

        $this->assertCount(5, $collection);
        $this->assertSame(['a', 'b', 'c', 'd', 'e'], $collection->toArray());
    }

    /**
     * Merge another Collection with other collection.
     */
    public function testMergeTraversable()
    {   
        $elements = ['a', 'b'];
        $mergeElements = ['c', 'd', 'e'];
        $collection = new Collection($elements);
        
        $collection->merge(new Collection($mergeElements));

        $this->assertCount(5, $collection);
        $this->assertSame(['a', 'b', 'c', 'd', 'e'], $collection->toArray());
    }

    /**
     * Merge another Collection with other collection.
     */
    public function testMergeDeepCollection()
    {   
        $elements = new Collection(['a', 'b', new Collection([1, 2])]);
        $mergeElements = ['c', 'd', 'e', 'assoc' => $elements];
        $collection = new Collection($elements);
        
        $collection->merge(new Collection($mergeElements));

        $this->assertCount(7, $collection);
        $this->assertSame(['a', 'b', [1, 2], 'c', 'd', 'e', 'assoc' => ['a', 'b', [1, 2]]], $collection->toArray());
    }

    /**
     * Merge another Collection with other collection.
     */
    public function testMergeDeepCollectionAssoc()
    {   
        $elements = [
            'replace' => 'me',
            'collection' => new Collection(['a', 'b']),
            'noCollection' => 'replaced'
        ];
        $mergeElements = [
            'replace' => 'toNew',
            'collection' => new Collection(['c', 'd', 'e']),
            'noCollection' => new Collection(['a', 'b'])
        ];
        $collection = new Collection($elements);
        
        $collection->merge(new Collection($mergeElements));

        $this->assertCount(3, $collection);
        $this->assertSame([
            'replace' => 'toNew',
            'collection' => ['a', 'b', 'c', 'd', 'e'],
            'noCollection' => ['a', 'b']
        ], $collection->toArray());
    }

    /**
     * Merge collection with null as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "NULL" given
     */
    public function testMergeInputNull()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->merge(null);
    }

    /**
     * Merge collection with integer as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "integer" given
     */
    public function testMergeInputInteger()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->merge(1);
    }

    /**
     * Merge collection with string as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "string" given
     */
    public function testMergeInputString()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->merge('string');
    }

    /**
     * Merge collection with object as an input
     *
     * @expectedException \Xloit\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Expected argument "array" or "Traversable"; "object(stdClass)" given
     */
    public function testMergeInputObject()
    {
        $collection = new Collection();

        /** @noinspection PhpParamsInspection */
        $collection->merge(new \stdClass());
    }

    /**
     * Serialize the collection
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testSerialize($elements)
    {
        $collection = new Collection($elements);
        $serializedElements = serialize($elements);
        $serializedCollection = serialize($collection);

        $this->assertNotSame($serializedElements, $serializedCollection);
        $this->assertInternalType('string', $serializedCollection);
        $this->assertTrue(strpos($serializedCollection, $serializedElements) > 0);
        $this->assertTrue(strpos($serializedCollection, Collection::class) > 0);
    }

    /**
     * Unserialize the collection
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testUnserialize($elements)
    {
        $collection = new Collection($elements);
        $serializedElements = serialize($elements);
        $serializedCollection = serialize($collection);
        $unSerializedElements = unserialize($serializedElements);
        /** @var Collection $unSerializedCollection */
        $unSerializedCollection = unserialize($serializedCollection);

        $this->assertInstanceOf(Collection::class, $unSerializedCollection);
        $this->assertEquals($unSerializedElements, $unSerializedCollection->toArray());
    }

    /**
     * Creates and returns a copy of this object. The precise meaning of "copy" may depend on the class of the object.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testClone($elements)
    {
        $collection = new Collection($elements);
        $clonedCollection = clone $collection;

        $this->assertInstanceOf(Collection::class, $clonedCollection);
        $this->assertEquals($collection, $clonedCollection);
        $this->assertNotSame($collection, $clonedCollection);
        $this->assertSame($collection->toArray(), $clonedCollection->toArray());

        $clonedCollection->set(null, 'new');
        $this->assertNotEquals($collection, $clonedCollection);
        $this->assertNotSame($collection->toArray(), $clonedCollection->toArray());
    }

    /**
     * Creates and returns a deep copy of this object.
     *
     * @dataProvider provideDifferentElements
     *
     * @param array $elements
     */
    public function testDeepClone($elements)
    {   
        $elements = [
            'deep' => new Collection($elements)
        ];
        $collection = new Collection($elements);
        $clonedCollection = clone $collection;

        $this->assertInstanceOf(Collection::class, $clonedCollection->get('deep'));
        $this->assertEquals($collection->get('deep'), $clonedCollection->get('deep'));
        $this->assertNotSame($collection->get('deep'), $clonedCollection->get('deep'));
        $this->assertSame($collection->get('deep')->toArray(), $clonedCollection->get('deep')->toArray());
    }

    /**
     * @return array
     */
    public function provideDifferentElements()
    {
        return [
            'indexed'     => [
                [
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            ],
            'associative' => [
                [
                    'A' => 'a',
                    'B' => 'b',
                    'C' => 'c',
                    'D' => 'd',
                    'E' => 'e'
                ]
            ],
            'mixed'       => [
                [
                    'A' => 'a',
                    'B' => 'b',
                    3,
                    4,
                    5
                ]
            ]
        ];
    }
}
