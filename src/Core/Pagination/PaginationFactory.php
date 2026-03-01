<?php

namespace App\Core\Pagination;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

interface PaginationFactory
{
    /**
     * @param SlidingPagination<int, object> $pagination
     * @param object[]|null                  $items
     */
    public function create(SlidingPagination $pagination, ?array $items = null): Pagination;
}
