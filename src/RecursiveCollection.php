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

use Traversable;

/**
 * A {@link RecursiveCollection} class.
 *
 * @package Xloit\Collection
 */
class RecursiveCollection extends Collection
{
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
        if (is_array($element)
            || ($element instanceof Traversable && !($element instanceof CollectionInterface))
        ) {
            $element = new static($element);
        }

        parent::set($index, $element);

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
        if (is_array($element)
            || ($element instanceof Traversable && !($element instanceof CollectionInterface))
        ) {
            $element = new static($element);
        }

        parent::prepend($element);

        return $this;
    }
}
