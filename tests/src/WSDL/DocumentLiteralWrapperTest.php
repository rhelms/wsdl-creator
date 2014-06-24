<?php
use Mocks\MockClass;
use WSDL\DocumentLiteralWrapper;

class DocumentLiteralWrapperTest extends PHPUnit_Framework_TestCase
{
    private $_handler;
    private $_unwrapped;

    public function setUp()
    {
        parent::setUp();
        $this->_handler = new DocumentLiteralWrapper(new MockClass());
        $this->_unwrapped = new MockClass();
    }

    /**
     * @test
     */
    public function shouldWrapReturn()
    {
        //when
        $result = $this->_handler->arrayOfMockUser();

        //then
        $this->assertTrue(isset($result->mockUsers));
        $this->assertEquals($result->mockUsers, $this->_unwrapped->arrayOfMockUser());
    }

    /**
     * @test
     */
    public function shouldNotWrapReturn()
    {
        //when 1
        $params = new stdClass();
        $params->a = 1;
        $params->b = 1;
        $result = $this->_handler->sum($params);

        //then 1
        $this->assertFalse(is_object($result));
        $this->assertEquals(2, $result);

        //when 2
        $params = new stdClass();
        $params->a = 1;
        $result = $this->_handler->noReturnFunction($params);

        //then 2
        $this->assertNull($result);

        //when 3
        $params = new stdClass();
        $params->a = 1;
        $result = $this->_handler->voidReturnFunction($params);

        //then 3
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldHandleSimpleArray()
    {
        $arg = new stdClass();
        $arg->max = 2;
        $result = $this->_handler->countTo($arg);

        $this->assertTrue(isset($result->count));
        $this->assertEquals($result->count, $this->_unwrapped->countTo(2));

        $expected = new stdClass();
        $expected->count = array('Number: 1', 'Number: 2');
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldHandleWrapperArray()
    {
        $result = $this->_handler->getEmployees();

        $expected = (object)array(
            'employees' => array(
                \Mocks\Employee::fromArray(array('id' => 3, 'department' => 'IT')),
                \Mocks\Employee::fromArray(array('id' => 4, 'department' => 'Logistics')),
                \Mocks\Employee::fromArray(array('id' => 5, 'department' => 'Management'))
        ));

        $this->assertEquals($expected, $result);

        $this->assertEquals($result->employees, $this->_unwrapped->getEmployees());
    }

    /**
     * @test
     */
    public function shouldHandleObjectArray()
    {
        $result = $this->_handler->getEmployeesWithAgents();

        $expected = (object)array(
            'employeesList' => array(
                (object)array(
                    'agents' => array(
                        \Mocks\Agent::fromArray(array(
                            'name' => 'agent1',
                            'number' => null
                        )),
                        \Mocks\Agent::fromArray(array(
                            'name' => 'agent2',
                            'number' => null
                        ))
                    )
                ),
                (object)array(
                    'agents' => array(
                        \Mocks\Agent::fromArray(array(
                            'name' => 'agent3',
                            'number' => null
                        )),
                        \Mocks\Agent::fromArray(array(
                            'name' => 'agent4',
                            'number' => null
                        ))
                    )
                )
            )
        );

        $this->assertEquals($expected, $result);

        $this->assertEquals($result->employeesList, $this->_unwrapped->getEmployeesWithAgents());
    }
}
