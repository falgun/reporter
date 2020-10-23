<?php
declare(strict_types=1);

namespace Falgun\Reporter;

class ProdReporter implements ReporterInterface
{

    public function isReportable(): bool
    {
        return false;
    }

    /**
     * @param mixed $details
     * @return void
     */
    public function addSqlDetails($details): void
    {
        return;
    }

    public function getCurrentController(): string
    {
        return '';
    }

    public function getCurrentMethod(): string
    {
        return '';
    }

    public function getCurrentTemplate(): string
    {
        return '';
    }

    public function getCurrentView(): string
    {
        return '';
    }

    public function memoryUsage(): float
    {
        return 0.00;
    }

    public function setCurrentController(string $controller): void
    {
        return;
    }

    public function setCurrentMethod(string $model): void
    {
        return;
    }

    public function setCurrentTemplate(string $templateName): void
    {
        return;
    }

    public function setCurrentView(string $view): void
    {
        return;
    }

    public function showReport(): void
    {
        return;
    }
}
