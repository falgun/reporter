<?php
declare(strict_types=1);

namespace Falgun\Reporter\Tests;

use Falgun\Http\Request;
use Falgun\Reporter\DevReporter;
use PHPUnit\Framework\TestCase;

final class DevReporterTest extends TestCase
{

    public function prepareRequest(bool $renderable = false): Request
    {
        $_SERVER = [
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '8080',
            'SERVER_ADDR' => '127.0.0.1',
            'HTTP_HOST' => 'localhost',
            'REQUEST_URI' => '/falgun-skeleton/public/?test=true',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'test=true',
            'REQUEST_SCHEME' => 'http',
            'SCRIPT_FILENAME' => '/home/user/falgun-skeleton/public/index.php',
            'SCRIPT_NAME' => '/falgun-skeleton/public/index.php',
            'PHP_SELF' => '/falgun-skeleton/public/index.php',
            'HTTP_ACCEPT' => $renderable ? 'text/html' : 'text/none'
        ];
        $_GET = ['test' => 'true'];
        $_POST = ['post' => 'true'];
        $_COOKIE = [];
        $_FILES = [];


        return Request::createFromGlobals();
    }

    public function prepareReporter(): DevReporter
    {
        $startTime = \microtime(true);
        $startMemory = \round(\memory_get_peak_usage(false) / 1024 / 1024, 2);
        $request = $this->prepareRequest();

        return new DevReporter($request, $startTime, $startMemory);
    }

    public function prepareRenderableReporter(): DevReporter
    {
        $startTime = \microtime(true);
        $startMemory = \round(\memory_get_peak_usage(false) / 1024 / 1024, 2);
        $request = $this->prepareRequest(true);

        return new DevReporter($request, $startTime, $startMemory);
    }

    public function testEmptyDevReporter()
    {
        $reporter = $this->prepareReporter();

        $this->expectOutputString("");
        unset($reporter);
    }

    public function testRenderableDevReporter()
    {
        $reporter = $this->prepareRenderableReporter();

        $reporter->setCurrentController(self::class);
        $reporter->setCurrentMethod(__FUNCTION__);
        $reporter->setCurrentView('/src/views/controller/file.php');
        $reporter->setCurrentTemplate('SiteTemplate');

        ob_start();
        unset($reporter);
        $html = ob_get_clean();

        $this->assertStringContainsString('Execution Time:', $html);
        $this->assertStringContainsString('Highest Memory used:', $html);
        $this->assertStringContainsString('Memory used by App:', $html);
        $this->assertStringContainsString('Loaded Controller: ' . self::class, $html);
        $this->assertStringContainsString('Loaded Method: ' . __FUNCTION__, $html);
        $this->assertStringContainsString('View File : /src/views/controller/file.php', $html);
        $this->assertStringContainsString('Template Name : SiteTemplate', $html);
        $this->assertStringContainsString('Total Query Executed: 0 SQL', $html);
        $this->assertStringContainsString('DB Queries: []', $html);
    }

    public function testControllerNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentController(self::class);

        $this->assertSame(self::class, $reporter->getCurrentController());
    }

    public function testMethodNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentMethod(__FUNCTION__);

        $this->assertSame(__FUNCTION__, $reporter->getCurrentMethod());
    }

    public function testTemplateNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentTemplate('TemplateA');

        $this->assertSame('TemplateA', $reporter->getCurrentTemplate());
    }

    public function testViewNameGetSet()
    {
        $reporter = $this->prepareReporter();

        $reporter->setCurrentView('/src/views');

        $this->assertSame('/src/views', $reporter->getCurrentView());
    }

    public function testAddSqlDetails()
    {
        $reporter = $this->prepareReporter();

        $reporter->addSqlDetails(['sql' => 'SELECT * FROM users']);

        $this->expectOutputString("");
        $reporter->showReport();
    }

    public function testMemoryUsage()
    {
        $reporter = $this->prepareReporter();
        $memoryUsage = $reporter->memoryUsage();

        $this->assertTrue(is_float($memoryUsage));
        $this->assertSame(round($memoryUsage, 2), $memoryUsage);
    }

    public function testIsReportable()
    {
        $reporter = $this->prepareReporter();

        $this->assertSame(false, $reporter->isReportable());
    }
}
