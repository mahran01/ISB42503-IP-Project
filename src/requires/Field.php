<?php
enum Type
{
    case Integer;
    case Currency;
    case String;
    case Select;
    case Array;
}
class Field
{
    public $name, $desc, $type, $min, $max, $value, $error, $fromSession, $isArray;

    public function __construct($name, $desc, $type, $min, $max, $fromSession = false)
    {
        $this->isArray = substr($name, strlen($name) - 2) == "[]";
        $this->name = $this->isArray ? substr($name, 0, strlen($name) - 2) : $name;
        $this->desc = $desc;
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
        $this->fromSession = $fromSession;
    }

    public function validateError(&$errors)
    {

        $name = $this->name;
        $desc = $this->desc;
        $type = $this->type;
        $min = $this->min;
        $max = $this->max;
        $error = &$this->error;
        $fromSession = $this->fromSession;
        $isArray = $this->isArray;

        $desc = '<b>'.ucfirst($desc).'</b>';
        if (!postExists($name) && !$fromSession)
        {
            $error = true;
            $errors[] = "Field {$desc} not exist.";
            return;
        }
        if (!sessionExists($name) && $fromSession)
        {
            $error = true;
            $errors[] = "Field {$desc} not exist.";
            return;
        }

        if ($fromSession) 
        {
            $this->value = &$_SESSION[$name];
        }
        else 
        {
            $this->value = &$_POST[$name];
        }
        
        //@TODO for array /////////////////////////////////////////////////////////////////
        if ($isArray)
        {
            return;
        }

        $value= &$this->value;
        
        if ($type === Type::Select)
        {
            if (empty($value) && $value != 0)
            {
                $error = true;
                $errors[] = "Field {$desc} is not selected.";
                return;
            }
            return;
        }

        if ($type === Type::String)
        {   
            //Check if value is empty for string - @UNNECESSARY
            if (empty($value) && $value != 0 && !($value === "" && $min == 0))
            {
                $error = true;
                $errors[] = "Field {$desc} is empty.";
                return;
            }
        }
        else
        {
            //Check if value is empty
            if (empty($value) && $value != 0)
            {
                $error = true;
                $errors[] = "Field {$desc} is empty.";
                return;
            }

            //Check if value is an Integer
            if ($type === Type::Integer)
            {
                if (!preg_match("/^\d+$/", $value))
                {
                    $error = true;
                    $errors[] = "$desc should contains only [0 - 9].";
                    return;
                }
            }
            //Check if value is a Currency
            if ($type === Type::Currency)
            {
                //Improvised isNumeric which reject trailing and leading whitespace
                $isNumeric = fn($value) => ((trim($value) === $value) && is_numeric($value));
                // $isNumeric = fn($value) => ((trim($value) === $value) && (number_format($value) == $value || is_numeric($value) == $value)); //@TODO Trying for comma but failed
                //Check if currency is a number
                if(!$isNumeric($value))
                {
                    $error = true;
                    $errors[] = "$desc should be valid number.";
                    return;
                }
                //Check if currency have only up to 2 decimal places
                if (!preg_match("/^\d+(\.\d{1,2})?$/", $value))
                {
                    $error = true;
                    $errors[] = "$desc should contains two or less decimal places.";
                    return;
                }
                $value = number_format($value, 2, ".", "");
            }
        }
        
        switch ($type)
        {
            case Type::String: [$ex, $n] = ["length", strlen($value)]; break;
            case Type::Currency:
            case Type::Integer: [$ex, $n] = ["value", $value]; break;
            default: [$ex, $n] = ["", 0];
        }
        if ($n < $min)
        {
            $error = true;
            $errors[] = "$desc $ex must be more than $min";
            return;
        }
        if ($n > $max)
        {
            $error = true;
            $errors[] = "$desc $ex must be less than $max";
            return;
        }
    }
}
?>