<?php

namespace Rcsofttech85\TicTacToe;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

#[AsCommand(
    name: 'tic-tac',
    description: 'Play a game of Tic Tac Toe',
)]
class TicTacToeCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln(
            "
 ___________.____________   ________________  _________   ___________           ___________
\__    ___/|   \_   ___ \   \__    ___/  _  \ \_   ___ \  \__    ___/    ___    \_   _____/
  |    |   |   /    \  \/     |    | /  /_\  \/    \  \/    |    |    /   |   \ |    __)_ 
  |    |   |   \     \____    |    |/    |    \     \____   |    |   /    |    \|        \
  |____|   |___|\______  /    |____|\____|__  /\______  /   |____|   \_________/|_______  /
                       \/                   \/        \/                               \/ 
        "
        );

        $board = [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ];

        $players = ['X', 'O'];
        $currentPlayerIndex = 0;

        while (true) {
            $this->displayBoard($board, $output);

            $currentPlayer = $players[$currentPlayerIndex];

            $move = $this->getUserMove($currentPlayer, $input, $output);

            $previousPlayer = $players[1 - $currentPlayerIndex];

            if ($board[$move['row']][$move['col']] === $this->coloredPlayer($previousPlayer)) {
                $io->error("Invalid Move");
                continue;
            }

            $board[$move['row']][$move['col']] = $this->coloredPlayer($currentPlayer);

            if ($this->checkWin($board, $this->coloredPlayer($currentPlayer))) {
                $this->displayBoard($board, $output);
                $io->writeln("$currentPlayer wins!");
                break;
            }

            if ($this->checkDraw($board)) {
                $this->displayBoard($board, $output);
                $io->writeln("It's a draw!");
                break;
            }

            $currentPlayerIndex = 1 - $currentPlayerIndex;
        }

        return Command::SUCCESS;
    }

    private function displayBoard(array $board, OutputInterface $output)
    {
        foreach ($board as $row) {
            $output->writeln(implode(' | ', $row));
            $output->writeln('---------');
        }
    }

    private function getUserMove(string $currentPlayer, InputInterface $input, OutputInterface $output): array
    {
        $helper = $this->getHelper('question');
        $question = "Player $currentPlayer's turn. Enter row (0-2) and column (0-2) separated by a space: ";

        $validation = Validation::createCallable(
            new Regex([
                'pattern' => '/^[0-2] [0-2]$/',
                'message' => 'Enter row (0-2) and column (0-2) separated by a space'
            ])
        );

        $move = $helper->ask($input, $output, (new Question($question))->setValidator($validation));

        list($row, $col) = explode(' ', $move);

        return ['row' => (int)$row, 'col' => (int)$col];
    }

    private function checkWin(array $board, string $currentPlayer): bool
    {
        for ($i = 0; $i < 3; $i++) {
            if (
                $board[$i][0] === $currentPlayer &&
                $board[$i][1] === $currentPlayer &&
                $board[$i][2] === $currentPlayer
            ) {
                return true; // Row win
            }

            if (
                $board[0][$i] === $currentPlayer &&
                $board[1][$i] === $currentPlayer &&
                $board[2][$i] === $currentPlayer
            ) {
                return true;
            }
        }

        if (
            $board[0][0] === $currentPlayer &&
            $board[1][1] === $currentPlayer &&
            $board[2][2] === $currentPlayer
        ) {
            return true;
        }

        if (
            $board[0][2] === $currentPlayer &&
            $board[1][1] === $currentPlayer &&
            $board[2][0] === $currentPlayer
        ) {
            return true;
        }

        return false;
    }

    private function checkDraw(array $board): bool
    {
        foreach ($board as $row) {
            foreach ($row as $cell) {
                if (empty($cell)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function coloredPlayer(string $player):string{

        return match($player){
            "X" => "\033[31m$player\033[0m",
            "O" => "\033[33m$player\033[0m"
        };
    }


}