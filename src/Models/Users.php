<?php

declare(strict_types=1);

namespace Invo\Models;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

class Users extends Model
{
    /**
     * @var integer|null
     */
    public ?int $id = null;

    /**
     * @var string
     */
    public string $username;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var string
     */
    public string $role;

    /**
     * @var string|RawValue
     */
    public string|RawValue $created_at;

}
