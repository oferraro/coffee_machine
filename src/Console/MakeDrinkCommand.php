<?php

namespace Deliverea\CoffeeMachine\Console;
include_once './src/Classes.php';

use Deliverea\CoffeeMachine\Chocolate;
use Deliverea\CoffeeMachine\Tea;
use Deliverea\CoffeeMachine\Coffee;
use Deliverea\CoffeeMachine\Machine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeDrinkCommand extends Command
{
    protected static $defaultName = 'app:order-drink';

    protected function configure()
    {
        $this->addArgument(
            'drink-type',
            InputArgument::REQUIRED,
            'The type of the drink. (Tea, Coffee or Chocolate)'
        );

        $this->addArgument(
            'money',
            InputArgument::REQUIRED,
            'The amount of money given by the user'
        );

        $this->addArgument(
            'sugars',
            InputArgument::OPTIONAL,
            'The number of sugars you want. (0, 1, 2)',
            0
        );

        $this->addOption(
            'extra-hot',
            'e',
            InputOption::VALUE_NONE,
            $description = 'If the user wants to make the drink extra hot'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drinkType = strtolower($input->getArgument('drink-type'));
        $money = $input->getArgument('money');
        $sugars = $input->getArgument('sugars');
        $extraHot = $input->getOption('extra-hot');

        // TODO: USE A FACTORY FOR THE DRINK TYPE
        switch ($drinkType) {
            case 'tea':
                $theDrink = new Tea();
                break;
            case 'coffee':
                $theDrink = new Coffee();
                break;
            case 'chocolate':
                $theDrink = new Chocolate();
                break;
            default:
                $theDrink = false;
        }
        if (!$theDrink) {
            $output->writeln('The drink type should be tea, coffee or chocolate.');
        } else {
            $machine = new Machine($money, $sugars, $extraHot);
            $userOutput  = "You have ordered a $drinkType ";
            try {
                $drink = $machine->makeDrink($theDrink);
            } catch (\Exception $error) {
                die($error->getMessage());
            }
            $userOutput .= ($machine->isExtraHot() ? ' extra hot ' : ' ');
            $userOutput .= ('with ' . $machine->getSugars() . ' sugars ');
            $userOutput .= (' ' . $machine->hastStick() ? '(stick included)' : '');
            $output->write($userOutput);
        }

    }
}
