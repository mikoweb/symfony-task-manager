<?php

namespace App\Application\Task\Controller;

use App\Application\Task\Dto\ChangeTaskStatusDto;
use App\Application\Task\Dto\CreateTaskDto;
use App\Application\Task\Dto\TaskDetailsDto;
use App\Application\Task\Dto\TaskHistoryDto;
use App\Application\Task\Dto\UpdateTaskDto;
use App\Application\Task\Interaction\Command\ChangeTaskStatus\ChangeTaskStatusCommand;
use App\Application\Task\Interaction\Command\CreateTask\CreateTaskCommand;
use App\Application\Task\Interaction\Command\DeleteTask\DeleteTaskCommand;
use App\Application\Task\Interaction\Command\UpdateTask\UpdateTaskCommand;
use App\Core\Api\Dto\OpenApiPagination;
use App\Core\Api\Dto\SuccessDto;
use App\Core\Api\Dto\SuccessWithIdDto;
use App\Core\Bus\CommandBus;
use App\Core\Controller\RestController;
use App\Core\Mapper\ObjectMapper;
use App\Domain\Task\Entity\Task;
use App\Domain\User\UserRoleName;
use App\Infrastructure\Task\Query\Decorator\HistoryTaskQueryDecorator;
use App\Infrastructure\Task\Query\ListHistoryTaskQueryFactory;
use App\Infrastructure\Task\Query\ListTaskQueryFactory;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Workflow\WorkflowInterface;

#[IsGranted(UserRoleName::ROLE_ADMIN)]
final class AdminTaskController extends RestController
{
    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Response(
        response: 200,
        description: 'Task details',
        content: new OA\JsonContent(
            ref: new Model(type: TaskDetailsDto::class),
            type: 'object',
        )
    )]
    public function get(
        Task $task,
        ObjectMapper $objectMapper,
    ): Response {
        return $objectMapper->map($task, TaskDetailsDto::class, ['list']) |> $this->json(...);
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Parameter(name: 'page', in: 'query')]
    #[OA\Parameter(name: 'limit', in: 'query')]
    #[OA\Response(
        response: 200,
        description: 'Paginated list of tasks',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'items', type: 'array', items: new OA\Items(
                    ref: new Model(type: TaskDetailsDto::class)
                )),
            ],
            type: 'object',
            anyOf: [new OA\Schema(ref: new Model(type: OpenApiPagination::class))]
        )
    )]
    public function list(
        Request $request,
        ListTaskQueryFactory $queryFactory,
        ObjectMapper $objectMapper,
    ): Response {
        return $this->paginate(
            $request,
            $queryFactory->create(),
            fn (array $tasks): array => $objectMapper->mapAny($tasks, TaskDetailsDto::class, ['list']),
            't.createdAt',
        );
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Post(requestBody: new OA\RequestBody(attachables: [new Model(type: CreateTaskDto::class)]))]
    #[OA\Response(
        response: 200,
        description: 'Task has been created',
        content: new OA\JsonContent(
            ref: new Model(type: SuccessWithIdDto::class),
            type: 'object',
        )
    )]
    public function create(
        #[MapRequestPayload] CreateTaskDto $dto,
        CommandBus $commandBus,
    ): Response {
        try {
            /** @var Uuid $id */
            $id = $commandBus->dispatch(new CreateTaskCommand($dto));
        } catch (HandlerFailedException) {
            return $this->createFailView('Unexpected error while creating task');
        }

        return $this->createSuccessWithIdView('Task has been created', $id->toString());
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Patch(requestBody: new OA\RequestBody(attachables: [new Model(type: UpdateTaskDto::class)]))]
    #[OA\Response(
        response: 200,
        description: 'Task has been updated',
        content: new OA\JsonContent(
            ref: new Model(type: SuccessDto::class),
            type: 'object',
        )
    )]
    public function update(
        #[MapRequestPayload] UpdateTaskDto $dto,
        Task $task,
        CommandBus $commandBus,
    ): Response {
        try {
            $commandBus->dispatch(new UpdateTaskCommand($task->getId(), $dto));
        } catch (HandlerFailedException) {
            return $this->createFailView('Unexpected error while updating task');
        }

        return $this->createSuccessView('Task has been updated');
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Response(
        response: 200,
        description: 'Task has been deleted',
        content: new OA\JsonContent(
            ref: new Model(type: SuccessDto::class),
            type: 'object',
        )
    )]
    public function delete(
        Task $task,
        CommandBus $commandBus,
    ): Response {
        try {
            $commandBus->dispatch(new DeleteTaskCommand($task->getId()));
        } catch (HandlerFailedException) {
            return $this->createFailView('Unexpected error while deleting task');
        }

        return $this->createSuccessView('Task has been deleted');
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Patch(requestBody: new OA\RequestBody(attachables: [new Model(type: ChangeTaskStatusDto::class)]))]
    #[OA\Response(
        response: 200,
        description: 'Task status has been changed',
        content: new OA\JsonContent(
            ref: new Model(type: SuccessDto::class),
            type: 'object',
        )
    )]
    public function changeStatus(
        #[MapRequestPayload] ChangeTaskStatusDto $dto,
        Task $task,
        CommandBus $commandBus,
        #[Target('task_status')]
        WorkflowInterface $statusWorkflow,
    ): Response {
        if (!$statusWorkflow->can($task, $dto->transitionName)) {
            throw $this->createAccessDeniedException(sprintf('Status transition `%s` is currently not possible', $dto->transitionName));
        }

        try {
            $commandBus->dispatch(new ChangeTaskStatusCommand($task->getId(), $dto));
        } catch (HandlerFailedException) {
            return $this->createFailView('Unexpected error while changing status');
        }

        return $this->createSuccessView('Task status has been changed');
    }

    #[OA\Tag(name: 'Task:Admin')]
    #[OA\Parameter(name: 'page', in: 'query')]
    #[OA\Parameter(name: 'limit', in: 'query')]
    #[OA\Response(
        response: 200,
        description: 'Paginated list of Task History',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'items', type: 'array', items: new OA\Items(
                    ref: new Model(type: TaskHistoryDto::class)
                )),
            ],
            type: 'object',
            anyOf: [new OA\Schema(ref: new Model(type: OpenApiPagination::class))]
        )
    )]
    public function listHistory(
        Request $request,
        Uuid $id,
        ListHistoryTaskQueryFactory $queryFactory,
        ObjectMapper $objectMapper,
    ): Response {
        return $this->paginate(
            $request,
            new HistoryTaskQueryDecorator($id, $queryFactory)->create(),
            fn (array $tasks): array => $objectMapper->mapAny($tasks, TaskHistoryDto::class, ['list']),
            'h.date',
        );
    }
}
