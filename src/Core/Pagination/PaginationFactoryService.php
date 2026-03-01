<?php

namespace App\Core\Pagination;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

final readonly class PaginationFactoryService implements PaginationFactory
{
    /**
     * @param SlidingPagination<int, object> $pagination
     * @param object[]|null                  $items
     */
    public function create(
        SlidingPagination $pagination,
        ?array $items = null,
    ): Pagination {
        $currentPage = $pagination->getCurrentPageNumber();

        return new Pagination(
            items: $items ?? (array) $pagination->getItems(),
            rawItems: (array) $pagination->getItems(),
            currentPageNumber: $currentPage,
            // @phpstan-ignore-next-line
            pageCount: $pagination->getPaginationData()['pageCount'],
            itemNumberPerPage: $pagination->getItemNumberPerPage(),
            totalItemCount: $pagination->getTotalItemCount(),
            offset: $currentPage * $pagination->getItemNumberPerPage() - $pagination->getItemNumberPerPage(),
        );
    }
}
