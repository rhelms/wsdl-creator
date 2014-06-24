<?php
namespace WSDL;

use BadMethodCallException;
use ReflectionMethod;
use stdClass;
use WSDL\Parser\MethodParser;
use WSDL\Types\Type;

/**
 * A helper class for wrapped document/literal clients to automatically
 * wrap a parameter list into a object or array paramter
 *
 * Uses the SoapClient to determine property mappings
 */
class DocumentLiteralWrapperClient
{
    private $__obj = null;

    private $__functions = null;

    private $__types = null;

    private $__functionTypeMap = null;

    public function __construct(\SoapClient $obj)
    {
        $this->__obj = $obj;

        $functions = $obj->__getFunctions();
        $this->__functions = array();
        $this->__types = array();
        foreach ($functions as $item) {
            // remove type. Anything before and including first space
            $parts = explode(' ', trim($item), 2);
            // remove params. Anything after and including first (
            $parts = explode('(', trim($parts[1]), 2);
            $function = trim($parts[0]);
            $this->__functions[] = $function;

            // extract the type. Anything before and excluding the first next space
            $parts = explode(' ', trim($parts[1]), 2);
            $type = trim($parts[0]);
            $this->__types[$type] = null;

            $this->__functionTypeMap[$function] = $type;
        }

        $types = $obj->__getTypes();
        foreach ($types as $item) {
            // determine type
            $parts = explode(' ', trim($item), 3);

            $type = $parts[1];
            $this->__types[$type] = array();

            // determine param names
            $parts = trim(str_replace(array('{', '}', "\t", "\n", "\r", "\0", "\x0B"), '', $parts[2]));
            $parts = explode(';', $parts);
            foreach ($parts as $param) {
                if (!empty($param)) {
                    $paramParts = explode(' ', trim($param), 2);
                    // order is significant, so store as indexed array
                    $this->__types[$type][] = $paramParts[1];
                }
            }
        }
    }

    public function __call($method, $args)
    {
        if (in_array($method, $this->__functions)) {
            $args = $this->_parseArgs($this->__functionTypeMap[$method], $args);
        }
        $return = call_user_func_array(array($this->__obj, $method), $args);

        return $return;
    }

    private function _parseArgs($type, $args)
    {
        $newArgs = array();
        foreach ($this->__types[$type] as $key => $paramName) {
            if (isset($args[$key])) {
                $newArgs[$paramName] = $args[$key];
            }
        }
        return array($newArgs);
    }
}
