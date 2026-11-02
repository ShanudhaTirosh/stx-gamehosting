<?php

namespace BrandedHosting\Platform\Exceptions;

use Exception;

/**
 * Thrown when an uploaded payment proof is refused for a reason that is safe to show to the customer.
 */
class ProofRejectedException extends Exception {}
