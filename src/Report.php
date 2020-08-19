<?php
declare(strict_types=1);

namespace Falgun\Reporter;

class Report
{

    public array $memoryUsage;
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

}
