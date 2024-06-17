<?php

declare(strict_types=1);

namespace Invo\Models;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

class Students extends Model
{
    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $username;

    /**
     * @var string
     */
    public string $email;

        /**
     * @var string
     */
    public string $department;

        /**
     * @var string
     */
    public string $major;

    /**
     * @var string|RawValue
     */
    public string|RawValue $created_at;

}
