<?php

namespace Deliverea\CoffeeMachine\Tests\Unit\Console;
include_once './src/Util/Classes.php';
include_once './src/CustomExceptions.php';

use Deliverea\CustomExceptions\CustomExceptions;
use Deliverea\Util\Drink;
use Deliverea\Util\GetSugars;
use PHPUnit\Framework\TestCase;

class MakeDrinkCommandTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testAssertTrue()
    {
        $this->assertTrue(true);
    }

    /* Makes a Chocolate with 2 sugars, with stick and extra hot */
    public function testGetChocolateExtraHot2Sugar() {
        $sugars = 2;
        $chocolate = new \Deliverea\Util\Chocolate();
        $machine = new \Deliverea\Util\Machine(2.3, $sugars, true);
        $drink = $machine->makeDrink($chocolate);
        $this->assertTrue($drink->getType() == Drink::TYPE_CHOCOLATE);
        $this->assertTrue($machine->isExtraHot());
        $this->assertTrue($machine->getSugars() == $sugars);
        $this->assertTrue($machine->hastStick());
    }

    /* Makes a Teat with no sugar and no stick */
    public function testGetTeaNoSugar() {
        $tea = new \Deliverea\Util\Tea();
        $machine = new \Deliverea\Util\Machine(1.3, 0);
        $drink = $machine->makeDrink($tea);
        $this->assertTrue($drink->getType() == Drink::TYPE_TEA);
        $this->assertFalse($machine->isExtraHot());
        $this->assertFalse($machine->hastStick());
    }

    /* Makes a coffee with 6 sugars, stick and not extra hot */
    public function testGetCoffee() {
        $sugars = 6;
        $coffee = new \Deliverea\Util\Coffee();
        $machine = new \Deliverea\Util\Machine(2.3, $sugars, false);
        $drink = $machine->makeDrink($coffee);
        $this->assertTrue($drink->getType() == Drink::TYPE_COFFEE);
        $this->assertFalse($machine->isExtraHot());
        $this->assertTrue($machine->getSugars() == $sugars);
        $this->assertTrue($machine->hastStick());
    }

    /* Make drink fails because of to many sugars */
    public function testGetCoffeeSugarException() {
        $coffee = new \Deliverea\Util\Coffee();
        $machine = new \Deliverea\Util\Machine(2.3, 22);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(CustomExceptions::SUGAR_AMOUNT);
        $drink = $machine->makeDrink($coffee);
    }

    /* Make drink fails because of not enough money */
    public function testMoneyException() {
        $coffee = new \Deliverea\Util\Coffee();
        $machine = new \Deliverea\Util\Machine(0.1, 2);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(CustomExceptions::MONEY);
        $drink = $machine->makeDrink($coffee);
    }
}
