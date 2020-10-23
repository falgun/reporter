<?php
declare(strict_types=1);

namespace Falgun\Reporter\Tests;

use Falgun\Reporter\ProdReporter;
use PHPUnit\Framework\TestCase;

final class ProdReporterTest extends TestCase
{

    public function prepareReporter(): ProdReporter
    {
        return new ProdReporter;
    }

    public function testProdReporter()
    {
        $reporter = $this->prepareReporter();

        $this->expectOutputString("");
        unset($reporter);
    }

    public function testDontShowReport()
    {
        $reporter = new ProdReporter;

        $this->expectOutputString("");
        $reporter->showReport();
    }

    public function testControllerNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentController(self::class);

        $this->assertSame('', $reporter->getCurrentController());
    }

    public function testMethodNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentMethod(__FUNCTION__);

        $this->assertSame('', $reporter->getCurrentMethod());
    }

    public function testTemplateNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentTemplate('TemplateA');

        $this->assertSame('', $reporter->getCurrentTemplate());
    }

    public function testViewNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentView('/src/views');

        $this->assertSame('', $reporter->getCurrentView());
    }

    public function testAddSqlDetails()
    {
        $reporter = $this->prepareReporter();

        $reporter->addSqlDetails(['sql' => 'SELECT * FROM users']);

        $this->expectOutputString('');
        $reporter->showReport();
    }

    public function testMemoryUsage()
    {
        $reporter = $this->prepareReporter();

        $this->assertSame(0.00, $reporter->memoryUsage());
    }

    public function testIsReportable()
    {
        $reporter = $this->prepareReporter();

        $this->assertSame(false, $reporter->isReportable());
    }
}
