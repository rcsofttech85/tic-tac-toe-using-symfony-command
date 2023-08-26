<?php

namespace Rcsofttech85\TicTacToe\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Rcsofttech85\TicTacToe\TicTacToeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class TicTacCommandTest extends TestCase
{
    private CommandTester|null $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $command = new TicTacToeCommand();

        $application = new Application();

        $application->add($command);

        $this->commandTester = new CommandTester($command);

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->commandTester = null;
    }

    #[Test]
    #[DataProvider('checkWinDataProvider')]
    #[TestDox('$player is the winner')]
    public function CheckWin(array $entries, string $player)
    {

        $this->commandTester->setInputs($entries);
        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString("$player wins!", $output);

    }


    public static function checkWinDataProvider(): array
    {
        return [
            'horizontal_win_1' => [

                ['0 0', '0 1', '1 0', '1 1', '2 0'],
                'X'
            ],
            'horizontal_win_2' => [

                ['0 0', '1 0', '2 1', '1 1', '2 0', '1 2'],
                'O'
            ],
            'horizontal_win_3' => [
                ['2 0', '1 0', '2 1', '1 1', '2 2'],
                'X'
            ],
            'column_win_1' => [
                ['0 0', '0 1', '2 0', '1 1', '1 0'],
                'X'
            ],
            'column_win_2' => [
                ['0 0', '0 1', '0 2 ', '1 1', '1 2', '2 1'],
                'O'
            ],
            'column_win_3' => [
                ['0 2', '0 1', '1 2 ', '1 1', '2 2'],
                'X'
            ],
            'diagonal_win_1' => [
                ['0 0', ' 0 1', '1 1', '1 0', '2 2'],
                'X'
            ],
            'diagonal_win_2' => [
                ['0 2', ' 0 1', '1 1', '1 0', '2 0'],
                'X'
            ]
        ];
    }


}