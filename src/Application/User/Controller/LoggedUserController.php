<?php

namespace App\Application\User\Controller;

use App\Application\User\Dto\UserDto;
use App\Core\Controller\RestController;
use App\Core\Mapper\ObjectMapper;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRoleName;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LoggedUserController extends RestController
{
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 200,
        description: 'Logged-in user data',
        content: new OA\JsonContent(
            ref: new Model(type: UserDto::class),
            type: 'object',
        )
    )]
    #[IsGranted(UserRoleName::ROLE_USER)]
    public function getUserData(ObjectMapper $objectMapper): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $objectMapper->map($user, UserDto::class, ['list']) |> $this->json(...);
    }
}
