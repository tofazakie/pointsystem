<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PointSystemTest extends TestCase
{
    /**
     * A login test.
     *
     * @return void
     */
    public function testLogin()
    {
        $parameters = [
            'email' => 'user1@mail.com',
            'password' => 'secret1'
        ];

        $this->post('/v1/login', $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'response_code'=> '00',
                'message' => 'success',
                'data' =>
                [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user'
                ]
            ]    
        );
    }


    /**
     * A set user point test.
     *
     * @return void
     */
    public function testSetUserPoint()
    {
        $parameters = [
            'point_type_id' => '1',
            'amount' => '4',
            'description' => 'order #4654'
        ];

        $this->post('/v1/setpoint', $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'response_code'=> '00',
                'message' => 'success',
                'data' =>
                [
                    '*' => [
                        'point_type_id',
                        'point_name',
                        'amount'
                    ]
                ]
            ]    
        );
    }
}
