<?php
use WSDL\DocumentLiteralWrapper;
use WSDL\WSDLCreator;
use WSDL\XML\Styles\DocumentLiteralWrapped;

require_once '../../vendor/autoload.php';

ini_set("soap.wsdl_cache_enabled", 0);

$wsdl = new WSDLCreator('SimpleSoapServer', 'http://localhost/wsdl-creator/examples/document_literal_wrapped/SimpleExampleSoapServer.php');
$wsdl->setNamespace("http://foo.bar/")->setBindingStyle(new DocumentLiteralWrapped());

if (isset($_GET['wsdl'])) {
    $wsdl->renderWSDL();
    exit;
}

$wsdl->renderWSDLService();

$server = new SoapServer('http://localhost/wsdl-creator/examples/document_literal_wrapped/SimpleExampleSoapServer.php?wsdl', array(
    'uri' => $wsdl->getNamespaceWithSanitizedClass(),
    'location' => $wsdl->getLocation(),
    'style' => SOAP_DOCUMENT,
    'use' => SOAP_LITERAL, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS
));
$server->setObject(new DocumentLiteralWrapper(new SimpleSoapServer()));
$server->handle();

class SimpleSoapServer
{
    /**
     * getNameWithAge
     *
     * Expected request
     * <getNameWithAge>
     *   <name>john</name>
     *   <age>5</age>
     * </getNameWithAge>
     *
     * Expected request
     * <getNameWithAgeResponse>
     *   <nameWithAge>Your name is: john and you are 5 years old</nameWithAge>
     * </getNameWithAgeResponse>
     *
     * @param string $name
     * @param int $age
     * @return string $nameWithAge
     */
    public function getNameWithAge($name, $age)
    {
        return 'Your name is: ' . $name . ' and you are ' . $age . ' years old';
    }

    /**
     * getNameForUsers
     *
     * Expected request
     * <getNameForUsers>
     *   <names>
     *     <name>john</name>
     *     <name>billy</name>
     *     <name>peter</name>
     *   </names>
     * </getNameForUsers>
     *
     * Expected response
     * <getNameForUsersResponse>
     *   <userNames>User names: john, billy, peter</userNames>
     * </getNameForUsersResponse>
     *
     * @param string[] $names
     * @return string $userNames
     */
    public function getNameForUsers($names)
    {
        //FIXME correct array of $names
        return 'User names: ' . implode(', ', $names);
    }

    /**
     * countTo
     *
     * Expected request
     * <countTo>
     *   <max>$max</max>
     * </countTo>
     *
     * Expected response
     * <countToResponse>
     *   <count>
     *     <count>Number: 1</count>
     *     ...
     *   </count>
     * </countToResponse>
     *
     * @param int $max
     * @return string[] $count
     */
    public function countTo($max)
    {
        //FIXME incorrect structure of response
        $array = array();
        for ($i = 0; $i < $max; $i++) {
            $array[] = 'Number: ' . ($i + 1);
        }
        return $array;
    }
}
