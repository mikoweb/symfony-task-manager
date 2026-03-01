<?php

namespace App\Core\Controller;

use App\Core\Api\Dto\FailDto;
use App\Core\Api\Dto\SuccessDto;
use App\Core\Api\Dto\SuccessWithIdDto;
use App\Core\Pagination\PaginationFactory;
use App\Core\Pagination\PaginationRequest;
use Doctrine\Common\Collections\Order;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class RestController extends AbstractController
{
    protected const string COMMON_EXCEPTION_MESSAGE = 'Something went wrong...';

    public function __construct(
        protected readonly PaginatorInterface $paginator,
        protected readonly PaginationFactory $paginationFactory,
    ) {
    }

    protected function createSuccessView(string $message, int $status = Response::HTTP_OK): JsonResponse
    {
        return $this->json(new SuccessDto($message), $status);
    }

    protected function createSuccessWithIdView(
        string $message,
        string $id,
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        return $this->json(new SuccessWithIdDto($message, $id), $status);
    }

    protected function createFailView(
        string $message = FailDto::COMMON_FAIL_MESSAGE,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
    ): JsonResponse {
        return $this->json(new FailDto($message), $status);
    }

    protected function paginate(
        Request $request,
        mixed $queryBuilder,
        callable $itemsFactory,
        string $defaultSort,
        string $defaultDirection = Order::Descending->value,
    ): Response {
        $paginationRequest = PaginationRequest::createFromRequest($request);

        /** @var SlidingPagination<int, object> $pagination */
        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $paginationRequest->page,
            $paginationRequest->limit,
            [
                'defaultSortFieldName' => $defaultSort,
                'defaultSortDirection' => $defaultDirection,
            ],
        );

        /** @var object[] $items */
        $items = $itemsFactory((array) $pagination->getItems());

        return $this->json(
            $this->paginationFactory->create(
                $pagination,
                $items,
            )
        );
    }
}
