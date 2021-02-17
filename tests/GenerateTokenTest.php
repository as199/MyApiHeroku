<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class TokenTest extends WebTestCase
{
    public function testSomething()
    {
        $login="admin1";
        $password ="pass1234";
        $client = static::createClient();
        $infos =["username=>$login","password"=>$password];

        $client->request(
            Request::METHOD_POST,
            '/login',
            [],
            [],
            ['ACCEPT'=>'application/json',
              'CONTENT_TYPE'=>'application/json'
            ],json_encode($infos));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $data = json_decode($client->getResponse()->getContent(),true);
       $client->setServerParameter('HTTP_AUTHORIZATION',\sprintf('Bearer %s'.$data['token']));

        return $client;
       /* $client = static::createClient();
        $client->request(
            'POST',
            '/login',
            [],
            [],
            ['username' => 'admin1',
                'password' => 'pass1234']
        );
       $data = $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
        $client->setServerParameter('HTTP_Authorization',\sprintf('Bearer %s'.$data['json_login']));
        return $client;*/
    }

}
