<?php

namespace Falgun\Reporter;

interface ReporterInterface
{

    public function memoryUsage(): float;

    public function pushToMemoryStack(string $key, $value = false): void;

    public function sqlDetails($details);

    public function setCurrentController(string $controller);

    public function setCurrentMethod(string $model);

    public function setCurrentTemplate(string $templateName);

    public function setCurrentView(string $view);

    public function getCurrentController(): string;

    public function getCurrentMethod(): string;

    public function getCurrentTemplate(): string;

    public function getCurrentView(): string;

    public function showReport();

    public function isReportable(): bool;
}
