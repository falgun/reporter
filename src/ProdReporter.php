<?php
declare(strict_types=1);

namespace Falgun\Reporter;

class ProdReporter extends DevReporter
{

    public function isReportable(): bool
    {
        return false;
    }
}
