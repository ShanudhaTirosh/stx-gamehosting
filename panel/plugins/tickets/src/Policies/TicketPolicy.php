<?php

namespace Boy132\Tickets\Policies;

use App\Policies\DefaultAdminPolicies;

class TicketPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'ticket';
}
