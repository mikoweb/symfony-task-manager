<?php

namespace App\Application\Task\Controller;

use App\Application\Task\Dto\ChangeTaskStatusDto;
use App\Application\Task\Dto\TaskDetailsDto;
use App\Application\Task\Interaction\Command\ChangeTaskStatus\ChangeTaskStatusCommand;
use App\Application\Task\Security\Voter\TaskOwnerVoter;
use App\Core\Api\Dto\OpenApiPagination;
use App\Core\Api\Dto\SuccessDto;
use App\Core\Bus\CommandBus;
use App\Core\Controller\RestController;
use App\Core\Mapper\ObjectMapper;
use App\Domain\Task\Entity\Task;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRoleName;
use App\Infrastructure\Task\Query\Decorator\UserTaskQueryDecorator;
use App\Infrastructure\Task\Query\ListTaskQueryFactory;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[IsGranted(UserRoleName::ROLE_USER)]
final class UserTaskController extends RestController
{
    #[OA\Tag(name: 'Task:User')]
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
        $this->denyAccessUnlessGranted(TaskOwnerVoter::IS_TASK_OWNER, $task, TaskOwnerVoter::MESSAGE_ILLEGAL_TASK);

        return $objectMapper->map($task, TaskDetailsDto::class, ['list']) |> $this->json(...);
    }

    #[OA\Tag(name: 'Task:User')]
    #[OA\Parameter(name: 'page', in: 'query')]
    #[OA\Parameter(name: 'limit', in: 'query')]
    #[OA\Response(
        response: 200,
        description: 'Paginated list of tasks assigned to you (logged user)',
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
        /** @var User $user */
        $user = $this->getUser();

        return $this->paginate(
            $request,
            new UserTaskQueryDecorator($user, $queryFactory)->create(),
            fn (array $tasks): array => $objectMapper->mapAny($tasks, TaskDetailsDto::class, ['list']),
            't.createdAt',
        );
    }

    #[OA\Tag(name: 'Task:User')]
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
        $this->denyAccessUnlessGranted(TaskOwnerVoter::IS_TASK_OWNER, $task, TaskOwnerVoter::MESSAGE_ILLEGAL_TASK);

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
}
