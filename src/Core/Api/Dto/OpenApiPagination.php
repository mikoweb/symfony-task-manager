<?php

namespace App\Core\Api\Dto;

/**
 * This class only for OpenApi\Attributes!
 */
final class OpenApiPagination
{
    public function __construct(
        public int $currentPageNumber,
        public int $pageCount,
        public int $itemNumberPerPage,
        public int $totalItemCount,
        public int $offset,
    ) {
    }
}
