<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Actions\Authentication\LoginUser;

final readonly class Login
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        return LoginUser::run($args['data']);
    }
}
