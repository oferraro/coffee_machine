<?php
// TODO: move, reorganize folders, files and namespaces

namespace Deliverea\CoffeeMachine;

use http\Cookie;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Deliverea\CoffeeMachine\CustomExceptions;


class Drink
{
    const TYPE_TEA = 0;
    const TYPE_COFFEE = 1;
    const TYPE_CHOCOLATE = 2;
    private $types = [self::TYPE_TEA, self::TYPE_COFFEE, self::TYPE_CHOCOLATE];

    public function __construct($type)
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception(CustomExceptions::DRINK_TYPE);
        }
        $this->type = $type;
    }

}

trait GetPrice
{
    public function getPrice()
    {
        return $this->price / 100;
    }
}

trait GetType
{
    public function getType()
    {
        return $this->type;
    }
}

class Tea extends Drink
{
    use GetPrice;

    // Use a trait to share getPrice method

    use GetType;

    private $price; // Divide by 100 to get price with decimals (for avoiding division/convertion errors)

    public function __construct()
    {
        parent::__construct(Drink::TYPE_TEA);
        $this->price = 40; // 0.40
    }

}

class Coffee extends Drink
{
    use GetPrice;

    // Use a trait to share getPrice method

    use GetType;

    private $price; // Divide by 100 to get price with decimals (for avoiding division/convertion errors)

    public function __construct()
    {
        parent::__construct(Drink::TYPE_COFFEE);
        $this->price = 50; // 0.50
    }

}

class Chocolate extends Drink
{
    use GetPrice;

    // Use a trait to share getPrice method

    use GetType;

    private $price; // Divide by 100 to get price with decimals (for avoiding division/convertion errors)

    public function __construct()
    {
        parent::__construct(Drink::TYPE_CHOCOLATE);
        $this->price = 60; // 0.60
    }

}

class Machine
{
    private $money;
    private $change = 0;
    private $sugar;
    private $extraHot;
    private $stick = false;

    function __construct($money, $sugar = 0, $extraHot = false)
    {
        $this->money = $money;
        $this->sugar = $sugar;
        $this->extraHot = $extraHot;
    }

    function makeDrink(Drink $drink)
    {
        $this->addSugar();
        if ($this->money < $drink->getPrice()) {
            throw new \Exception(CustomExceptions::MONEY);
        } else {
            $this->change = $this->money - $drink->getPrice();
        }
        return $drink;
    }

    private function addSugar()
    {
        if ($this->sugar < 0 || $this->sugar > 10) { // Let's assume a maximun of 10 sugar for drink
            throw new \Exception(CustomExceptions::SUGAR_AMOUNT);
        } elseif ($this->sugar > 0) {
            $this->stick = true;
        }
    }

    public function isExtraHot()
    {
        return $this->extraHot;
    }

    public function hastStick()
    {
        return $this->stick;
    }

    public function getSugars()
    {
        return $this->sugar;
    }
}
