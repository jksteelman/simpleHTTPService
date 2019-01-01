<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Request;

final class EmailTest extends TestCase
{
    /** @var \GuzzleHttp\Client */
    private $http;

    public function setUp() {
        $this->http = new GuzzleHttp\Client();
    }
    public function tearDown() {
        $this->http = null;
    }

    public function testList() {
        $response = $this->http->get('http://localhost/recipes');
        $this->assertEquals(200, $response->getStatusCode());
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertGreaterThanOrEqual(2, $content);
        $recipe_first = $content[0];
        $this->assertObjectHasAttribute('id', $recipe_first);
        $this->assertObjectHasAttribute('name', $recipe_first);
        $this->assertObjectHasAttribute('prep_time', $recipe_first);
        $this->assertObjectHasAttribute('difficulty', $recipe_first);
        $this->assertObjectHasAttribute('vegetarian', $recipe_first);
    }

    public function testView() {
        $response = $this->http->get('http://localhost/recipes');
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $oneId = $content[0]->id;

        $response = $this->http->get('http://localhost/recipes/'.$oneId);
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertObjectHasAttribute('id', $content);
        $this->assertObjectHasAttribute('name', $content);
        $this->assertObjectHasAttribute('prep_time', $content);
        $this->assertObjectHasAttribute('difficulty', $content);
        $this->assertObjectHasAttribute('vegetarian', $content);
    }

    public function testCreate() {
        $parameters = [
            '_auth_key' => getenv('ADMIN_KEY'),
            'name' => 'Hot Coco',
            'difficulty' => '1',
            'prep_time' => '2',
            'vegetarian' => true,
        ];
        $response = $this->http->post('http://localhost/recipes', ['form_params' => $parameters]);
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertObjectHasAttribute('name', $content);
        $this->assertEquals('Hot Coco', $content->name);
        $this->assertObjectHasAttribute('prep_time', $content);
        $this->assertEquals('2', $content->prep_time);
        $this->assertObjectHasAttribute('difficulty', $content);
        $this->assertEquals('1', $content->difficulty);
        $this->assertObjectHasAttribute('vegetarian', $content);
        $this->assertEquals('1', $content->vegetarian);
    }

    public function testDelete() {
        $response = $this->http->get('http://localhost/recipes');
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $oneId = $content[count($content) - 1]->id;

        $request = new Request('DELETE', 'http://localhost/recipes/'.$oneId, ['auth-key' =>  getenv('ADMIN_KEY')], null);
        $response = $this->http->send($request);
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertTrue($content->success);
    }

    public function testUpdate() {
        $response = $this->http->get('http://localhost/recipes');
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $oneId = $content[count($content) - 1]->id;
        $oldName = $content[count($content) - 1]->name;

        $parameters = [
            '_auth_key' => getenv('ADMIN_KEY'),
            'name' => 'Mango Lassi',
        ];
        $response = $this->http->post('http://localhost/recipes/'.$oneId, ['form_params' => $parameters]);
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertEquals('Mango Lassi', $content->name);

        $parameters = [
            '_auth_key' => getenv('ADMIN_KEY'),
            'name' => $oldName,
        ];
        $response = $this->http->post('http://localhost/recipes/'.$oneId, ['form_params' => $parameters]);
        $content = \Zend\Json\Json::decode((string) $response->getBody());
        $this->assertEquals($oldName, $content->name);
    }
}
