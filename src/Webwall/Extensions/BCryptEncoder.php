<?php

namespace Webwall\Extensions;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder as SyMessageDigestPasswordEncoder;

/**
 * Used to allow for use of existing passwords that were just sha1 in the db,
 */
class BCryptEncoder extends SyMessageDigestPasswordEncoder
{
    public function encodePassword($raw, $salt=null)
    {
        return password_hash($raw, PASSWORD_BCRYPT);
    }
}
