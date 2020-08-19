<?php
declare(strict_types=1);

namespace Falgun\Reporter;

class DevReporter
{

    protected Report $report;

    public function __construct()
    {
        $startTime = \microtime(true);
        $startMemory = $this->memoryUsage();

        $this->report = new Report();
        $this->report->startTime = $startTime;
        $this->report->startMemory = $startMemory;

        $this->pushToMemoryStack('init', $startMemory);
    }

    public function memoryUsage(): float
    {
        return \round(\memory_get_peak_usage(false) / 1024 / 1024, 2);
    }

    public function pushToMemoryStack(string $key, $value = false): void
    {
        if ($value === false) {
            $value = $this->memoryUsage();
        }

        $this->report->memoryUsage[] = array('class' => $key, 'memory' => $value);
    }

    public function sqlDetails($details)
    {
        $this->report->dbQueries[] = $details;
    }

    public function setCurrentController(string $controller)
    {
        $this->report->currentController = $controller;
    }

    public function setCurrentMethod(string $model)
    {
        $this->report->currentMethod = $model;
    }

    public function setCurrentTemplate(string $templateName)
    {
        $this->report->currentTemplate = $templateName;
    }

    public function setCurrentView(string $view)
    {
        $this->report->currentViewFile = $view;
    }

    public function getCurrentController(): string
    {
        return $this->report->currentController ?? '';
    }

    public function getCurrentMethod(): string
    {
        return $this->report->currentMethod ?? '';
    }

    public function getCurrentTemplate(): string
    {
        return $this->report->currentTemplate ?? '';
    }

    public function getCurrentView(): string
    {
        return $this->report->currentViewFile ?? '';
    }

    public function isReportable(): bool
    {
        return (\strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'GET' &&
            (\strpos(\strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'text/html') !== false) &&
            \strpos(\php_sapi_name(), 'cli') === false);
    }

    protected function getReportTemplate(): string
    {
        return \file_get_contents(__DIR__ . '/stub/viewReport.tpl');
    }

    protected function populateReportTemplate(string $template, array $properties): string
    {
        $propertyKeys = \array_map(function ($value) {
            return '{{' . $value . '}}';
        }, \array_keys($properties));
        $propertyValues = \array_values($properties);

        return \str_replace($propertyKeys, $propertyValues, $template);
    }

    public function showReport()
    {

        if ($this->isReportable() === false) {
            return false;
        }

        $properties = (array) $this->report;

        $properties['memoryUsage'] = \json_encode($properties['memoryUsage']);
        $properties['dbQueries'] = \json_encode($properties['dbQueries'] ?? []);
        $properties['additionalResources'] = \json_encode($properties['additionalResources'] ?? []);

        $properties['executionTime'] = ($this->report->endTime - $this->report->startTime);
        $properties['highestMemory'] = \max([$this->report->endMemory, $this->report->startMemory]);
        $properties['consumedMemory'] = ($this->report->endMemory - $this->report->startMemory);
        $properties['totalSqlExecuted'] = (!empty($this->report->dbQueries)) ? \count($this->report->dbQueries) : 0;

        $template = $this->getReportTemplate();

        echo $this->populateReportTemplate($template, $properties);
    }

    public function __destruct()
    {
        $this->report->endTime = \microtime(true);
        $this->report->endMemory = $this->memoryUsage();
        $this->showReport();
    }
}
