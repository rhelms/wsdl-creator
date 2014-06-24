<?php
namespace Clients\DocumentLiteralWrapped;

use Clients\InitCommand;
use SoapClient;
use stdClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WSDL\DocumentLiteralWrapperClient;

class SimpleCommand extends InitCommand
{
    protected function configure()
    {
        $this->setName('document_literal:simple');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->soapClient = new SoapClient('http://localhost/wsdl-creator/examples/document_literal_wrapped/SimpleExampleSoapServer.php?wsdl', array(
            'trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE
        ));

        //uncomment for DocumentLiteralWrapperClient
        //$this->soapClient = new DocumentLiteralWrapperClient($this->soapClient);

        $this->serviceInfo('Client Simple - document/literal wrapped');

        $this->renderMethodsTable();

        // stdClass method
        $params = new stdClass();
        $params->name = 'john';
        $params->age = 5;
        $response = $this->soapClient->getNameWithAge($params);
        $this->method('getNameWithAge', array($params), $response);

        // array method
        //$params = array('name' => 'john', 'age' => 5);
        //$response = $this->soapClient->getNameWithAge($params);
        // $this->method('getNameWithAge', array($params), $response);

        // DocumentLiteralWrapperClient method
        //$response = $this->soapClient->getNameWithAge('john', 5);
        //$this->method('getNameWithAge', array('john', 5), $response);

        $params = new stdClass();
        $params->names = array('john', 'billy', 'peter');
        $response = $this->soapClient->getNameForUsers($params);
        $this->method('getNameForUser', array($params), $response);

        $params = new stdClass();
        $params->max = 5;
        $response = $this->soapClient->countTo($params);
        $this->method('countTo', array($params), $response);

    }
}
