<?php
declare(strict_types=1);

namespace Falgun\Reporter;

final class Report
{

    public float $startTime;
    public float $endTime;
    public float $startMemory;
    public float $endMemory;
    public string $currentController;
    public string $currentMethod;
    public string $currentTemplate;
    public string $currentViewFile;
    public array $dbQueries;
    public array $additionalResources;

    public function __construct(
        float $startTime,
        float $startMemory,
        float $endTime = 0.00,
        float $endMemory = 0.00,
        string $currentController = '',
        string $currentMethod = '',
        string $currentTemplate = '',
        string $currentViewFile = '',
        array $dbQueries = [],
        array $additionalResources = []
    )
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->startMemory = $startMemory;
        $this->endMemory = $endMemory;
        $this->currentController = $currentController;
        $this->currentMethod = $currentMethod;
        $this->currentTemplate = $currentTemplate;
        $this->currentViewFile = $currentViewFile;
        $this->dbQueries = $dbQueries;
        $this->additionalResources = $additionalResources;
    }
}
