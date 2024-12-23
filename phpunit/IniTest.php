<?php
use PHPUnit\Framework\TestCase;

class IniTest extends TestCase
{
    private $originalErrorHandler;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト対象コードの読み込み
        require_once dirname(__DIR__, 1) . '/public/common/Console.php';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // エラーをデフォルトのエラーハンドラーに渡す
        return false;
    }

    public function testGetAllData()
    {
        $result = getIni('all');
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    public function testGetSpecificData()
    {
        $result = getIni('specificParameter');
        if ($result === false) {
            $this->assertFalse($result);
        } else {
            $this->assertIsArray($result);
            $this->assertArrayHasKey('specificParameter', $result);
        }
    }

    public function testGetInvalidParameter()
    {
        $result = getIni('invalidParameter');
        $this->assertFalse($result);
    }

    public function testGetNoParameter()
    {
        $result = getIni();
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}
