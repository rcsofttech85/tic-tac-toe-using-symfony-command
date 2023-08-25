<?php

namespace Rcsofttech85\TicTacToe\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Rcsofttech85\TicTacToe\TicTacToeCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TicTacCommandTest extends TestCase
{
    private TicTacToeCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new TicTacToeCommand();

    }

    #[Test]
    #[DataProvider('checkWinDataProvider')]
    #[TestDox('$player is the winner')]
    public function CheckWin(array $board, string $player)
    {

        $result = $this->invokeMethod($this->command, 'checkWin', [$board, $player]);
        $this->assertTrue($result);

    }

    #[Test]
    #[TestDox("test input value is assigned correctly")]
    public function getUserMove()
    {
        $inputMock = $this->createMock(InputInterface::class);
        $outputMock = $this->createMock(OutputInterface::class);
        $questionHelperMock = $this->createMock(QuestionHelper::class);

        $expectedInput = '1 1';
        $questionHelperMock->expects($this->once())
            ->method('ask')
            ->willReturn($expectedInput);



        $this->command->setHelperSet(new HelperSet(['question' => $questionHelperMock]));


        $result = $this->invokeMethod($this->command, 'getUserMove', ['X', $inputMock, $outputMock]);

        $expectedArray = ['row' => 1, 'col' => 1];

        $this->assertTrue($result === $expectedArray);
    }

    public static function checkWinDataProvider(): array
    {
        return [
            'row1' => [
                [
                    ['X', 'X', 'X'],
                    ['', '', ''],
                    ['', '', ''],
                ], 'X'
            ],
            'row2' => [
                [
                    ['', '', ''],
                    ['X', 'X', 'X'],
                    ['', '', ''],
                ], 'X'
            ],
            'row3' => [
                [
                    ['', '', ''],
                    ['', '', ''],
                    ['X', 'X', 'X'],
                ], 'X'
            ],
            'column1' => [
                [
                    ['X', '', ''],
                    ['X', '', ''],
                    ['X', '', ''],
                ], 'X'
            ],
            'column2' => [
                [
                    ['', 'X', ''],
                    ['', 'X', ''],
                    ['', 'X', ''],
                ], 'X'
            ],
            'column3' => [
                [
                    ['', '', 'X'],
                    ['', '', 'X'],
                    ['', '', 'X'],
                ], 'X'
            ],
            'diagonal1' => [
                [
                    ['X', '', ''],
                    ['', 'X', ''],
                    ['', '', 'X'],
                ], 'X'
            ],
            'diagonal2' => [
                [
                    ['', '', 'X'],
                    ['', 'X', ''],
                    ['X', '', ''],
                ], 'X'
            ]
        ];
    }


    private function invokeMethod(object &$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object::class);
        $method = $reflection->getMethod($methodName);

        return $method->invokeArgs($object, $parameters);
    }
}