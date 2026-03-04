<?php

namespace App\Core\Repository;

use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 *
 * @extends ObjectRepository<T>
 */
interface UniversalRepository extends ObjectRepository
{
}
