<?php
declare(strict_types=1);

namespace Falgun\Reporter;

class ProdReporter implements ReporterInterface
{

    public function isReportable(): bool
    {
        return false;
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

    public function pushToMemoryStack(string $key, $value = false): void
    {
        return;
    }

    public function setCurrentController(string $controller)
    {
        return;
    }

    public function setCurrentMethod(string $model)
    {
        return;
    }

    public function setCurrentTemplate(string $templateName)
    {
        return;
    }

    public function setCurrentView(string $view)
    {
        return;
    }

    public function showReport()
    {
        return;
    }

    public function sqlDetails($details)
    {
        return;
    }
}
