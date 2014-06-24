<?php
/**
 * MockClass
 *
 * @author Piotr Olaszewski <piotroo89 [%] gmail dot com>
 */
namespace Mocks;

use stdClass;

class MockClass
{
    function __construct()
    {
    }

    function __destruct()
    {
    }

    function __get($name)
    {
    }

    /**
     * @desc MethodParser to logging
     * @param string $message
     */
    private function _toLog($message)
    {
        file_put_contents('/tmp/logs_soap.log', $message);
    }


    /**
     * @desc to sum two integers
     * @param int $a
     * @param int $b
     * @return int
     */
    public function sum($a, $b)
    {
        return $a + $b;
    }

    /**
     * @param object $object1 @string=$name @int=$id
     * @return object $return @string=$new_name @int=$new_id
     */
    public function arrayTest($object1)
    {
        $o = new stdClass();
        $o->new_name = 'new:' . $object1->name;
        $o->new_id = $object1->id + 2;
        return $o;
    }

    /**
     * @param wrapper $wrap @className=\Mocks\MockUserWrapper
     * @return bool
     */
    public function methodWithWrapper($wrap)
    {
        return $wrap ? true : false;
    }

    /**
     * @return wrapper[] $mockUsers @className=\Mocks\MockUserWrapper
     */
    public function arrayOfMockUser() {
        $mockUsers = array();

        $o = new MockUserWrapper();
        $o->id = 1;
        $o->name = 'Fred';
        $o->age = 20;
        $mockUsers[] = $o;

        $o = new MockUserWrapper();
        $o->id = 2;
        $o->name = 'Murray';
        $o->age = 25;
        $mockUsers[] = $o;

        return $mockUsers;
    }

    /**
     * noReturnFunction
     *
     * @param int $a
     */
    public function noReturnFunction($a)
    {
        $a = $a + 1;
    }

    /**
     * voidReturnFunction
     *
     * @param int $a
     * @return void
     */
    public function voidReturnFunction($a)
    {
        $a = $a + 1;
    }

    /**
     * @param int $max
     * @return string[] $count
     */
    public function countTo($max) {
        $array = array();
        for ($i = 0; $i < $max; $i++) {
            $array[] = 'Number: ' . ($i + 1);
        }
        return $array;
    }

    /**
     * @return wrapper[] $employees @className=\Mocks\Employee
     */
    public function getEmployees()
    {
        $employees = array();
        $departments = array('IT', 'Logistics', 'Management');
        for ($i = 0; $i < 3; $i++) {
            $employee = new Employee();
            $employee->id = 2 + $i + 1;
            $employee->department = $departments[$i];
            $employees[] = $employee;
        }
        return $employees;
    }

    /**
     * @return object[] $employeesList @(wrapper[] $agents @className=\Mocks\Agent)
     */
    public function getEmployeesWithAgents()
    {
        $obj = array();
        $obj[0] = new stdClass();
        $obj[0]->agents[0] = new Agent();
        $obj[0]->agents[0]->name = 'agent1';
        $obj[0]->agents[1] = new Agent();
        $obj[0]->agents[1]->name = 'agent2';
        $obj[1] = new stdClass();
        $obj[1]->agents[0] = new Agent();
        $obj[1]->agents[0]->name = 'agent3';
        $obj[1]->agents[1] = new Agent();
        $obj[1]->agents[1]->name = 'agent4';
        return $obj;
    }
}

class Agent
{
    /**
     * @type string
     */
    public $name;
    /**
     * @type int
     */
    public $number;

    public static function fromArray($array) {
        $obj = new Agent();
        $obj->name = $array['name'];
        $obj->number = $array['number'];
        return $obj;
    }
}

class Employee
{
    /**
     * @type int
     */
    public $id;
    /**
     * @type string
     */
    public $department;

    public static function fromArray($array) {
        $obj = new Employee();
        $obj->id = $array['id'];
        $obj->department = $array['department'];
        return $obj;
    }
}
