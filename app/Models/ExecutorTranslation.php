<?php

namespace App\Models;

class ExecutorTranslation extends ModelActivityExtend
{
    const MODULE_NAME = ('custom.executor_translation');

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'executor_translation';
}
