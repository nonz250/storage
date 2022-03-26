<?php
declare(strict_types=1);

namespace Tests\Foundation\Model;

use InvalidArgumentException;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use PHPUnit\Framework\TestCase;
use Tests\StringTestHelper;

class BindValuesTest extends TestCase
{
    public function test__construct(): BindValues
    {
        $bindValues = new BindValues();

        $expectedKey1 = StringTestHelper::random();
        $expectedValue1 = StringTestHelper::random();
        $bindValues->bindValue($expectedKey1, $expectedValue1);

        $expectedKey2 = StringTestHelper::random();
        $expectedValue2 = mt_rand();
        $bindValues->bindValue($expectedKey2, $expectedValue2);

        $expectedKey3 = StringTestHelper::random();
        $expectedValue3 = mt_rand() % 2 === 1;
        $bindValues->bindValue($expectedKey3, $expectedValue3);

        $this->assertSame([
            $expectedKey1 => $expectedValue1,
            $expectedKey2 => $expectedValue2,
            $expectedKey3 => $expectedValue3,
        ], $bindValues->toArray());

        return $bindValues;
    }

    public function testKeyRequiredException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bindValues = new BindValues();
        $expectedKey1 = '';
        $expectedValue1 = StringTestHelper::random();
        $bindValues->bindValue($expectedKey1, $expectedValue1);
    }

    public function testOverlapException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $bindValues = new BindValues();

        $expectedKey1 = StringTestHelper::random();
        $expectedValue1 = StringTestHelper::random();
        $expectedValue2 = mt_rand();

        $bindValues->bindValue($expectedKey1, $expectedValue1);
        $bindValues->bindValue($expectedKey1, $expectedValue2);
    }

    /**
     * @depends test__construct
     * @param BindValues $bindValues
     * @return void
     */
    public function testIterator(BindValues $bindValues): void
    {
        foreach ($bindValues as $bindKey => $bindValue) {
            $this->assertIsString($bindKey);
        }
    }
}
