<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when an invitation is requested for an email address that
 * already belongs to a (possibly soft-deleted) user account.
 */
class UserAlreadyExistsException extends RuntimeException {}
