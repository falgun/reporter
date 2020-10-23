<?php
declare(strict_types=1);

namespace Falgun\Reporter;

use Falgun\Http\RequestInterface;

final class DevReporter implements ReporterInterface
{

    private Report $report;
    private RequestInterface $request;

    public function __construct(RequestInterface $request, float $startTime, float $startMemory)
    {
        $this->request = $request;
        $this->report = new Report($startTime, $startMemory);
    }

    public function memoryUsage(): float
    {
        return \round(\memory_get_peak_usage(false) / 1024 / 1024, 2);
    }

    /**
     * @param mixed $details
     * @return void
     */
    public function addSqlDetails($details): void
    {
        $this->report->dbQueries[] = $details;
    }

    public function setCurrentController(string $controller): void
    {
        $this->report->currentController = $controller;
    }

    public function setCurrentMethod(string $model): void
    {
        $this->report->currentMethod = $model;
    }

    public function setCurrentTemplate(string $templateName): void
    {
        $this->report->currentTemplate = $templateName;
    }

    public function setCurrentView(string $view): void
    {
        $this->report->currentViewFile = $view;
    }

    public function getCurrentController(): string
    {
        return $this->report->currentController;
    }

    public function getCurrentMethod(): string
    {
        return $this->report->currentMethod;
    }

    public function getCurrentTemplate(): string
    {
        return $this->report->currentTemplate;
    }

    public function getCurrentView(): string
    {
        return $this->report->currentViewFile;
    }

    public function isReportable(): bool
    {
        return ($this->request->getMethod() === 'GET' &&
            (\strpos(\strtolower($this->request->serverDatas()->get('HTTP_ACCEPT', '')), 'text/html') !== false));
    }

    private function getReportTemplate(): string
    {
        return \file_get_contents(__DIR__ . '/stub/viewReport.tpl');
    }

    private function populateReportTemplate(string $template, array $properties): string
    {
        $propertyKeys = \array_map(function ($value) {
            return '{{' . $value . '}}';
        }, \array_keys($properties));
        $propertyValues = \array_values($properties);

        return \str_replace($propertyKeys, $propertyValues, $template);
    }

    public function showReport(): void
    {

        if ($this->isReportable() === false) {
            return;
        }

        $properties = (array) $this->report;

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
