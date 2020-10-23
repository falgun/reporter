<?php

namespace Falgun\Reporter;

interface ReporterInterface
{

    public function memoryUsage(): float;

    /**
     * @param mixed $details
     * @return void
     */
    public function addSqlDetails($details): void;

    public function setCurrentController(string $controller): void;

    public function setCurrentMethod(string $model): void;

    public function setCurrentTemplate(string $templateName): void;

    public function setCurrentView(string $view): void;

    public function getCurrentController(): string;

    public function getCurrentMethod(): string;

    public function getCurrentTemplate(): string;

    public function getCurrentView(): string;

    public function showReport(): void;

    public function isReportable(): bool;
}
