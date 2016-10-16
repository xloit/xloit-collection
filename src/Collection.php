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

/**
 * A {@link Collection} class.
 *
 * A Collection resembles the nature of a regular PHP array. That is, it is essentially an ordered map
 * that can also be used like a list.
 *
 * A Collection has an internal iterator just like a PHP array. In addition, a Collection can be iterated
 * with external iterators, which is preferable. To use an external iterator simply use the foreach language
 * construct to iterate over the collection (which calls {@link Collection::getIterator()} internally) or
 * explicitly retrieve an iterator though {@link Collection::getIterator()} which can then be used to iterate
 * over the collection.
 *
 * You can not rely on the internal iterator of the collection being at a certain position unless you
 * explicitly positioned it before. Prefer iteration with external iterators.
 *
 * @package Xloit\Collection
 */
class Collection extends AbstractCollection
{
}
