<?php

namespace App\Infrastructure\Shared\Doctrine\Functions;

class Ilike extends BaseFunction
{
    protected function customiseFunction(): void
    {
        $this->setFunctionPrototype('%s ilike %s');
        $this->addNodeMapping('StringPrimary');
        $this->addNodeMapping('StringPrimary');
    }
}
