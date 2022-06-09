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
                'response_code',
                'message',
                'data' =>
                [
                    'access_token',
                    'token_type',
                    'expires_in'
                ]
            ]    
        );
    }


    /**
     * A failed login test.
     *
     * @return void
     */
     public function testFailedLogin()
    {
        $parameters = [
            'email' => 'user1@mail.com',
            'password' => 'secret3'
        ];

        $this->post('/v1/login', $parameters, []);
        $this->seeStatusCode(401);
        $this->seeJsonStructure(
            [
                'response_code',
                'message'
            ]    
        );
    }


    /**
     * A failed login test.
     *
     * @return void
     */
     public function testIncompleteParamsLogin()
    {
        $parameters = [
            'email' => 'user1@mail.com'
        ];

        $this->post('/v1/login', $parameters, []);
        $this->seeStatusCode(422);
        $this->seeJsonStructure(
            [
                'response_code',
                'message'
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
        // login user
        $response = $this->call('POST', '/v1/login', 
                    [
                        "email" => "user1@mail.com",
                        "password" => "secret1"
                    ], [], [], []);

        $response = json_decode($response->getContent());
        $token = $response->data->access_token;

        $headers = [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ];

        $parameters = [
            'point_type_id' => 1,
            'amount' => 3,
            'description' => 'order #4654'
        ];

        $this->post('/v1/userpoint', $parameters, $headers);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'response_code',
                'message'
            ]    
        );
    }

    /**
     * A get user points test.
     *
     * @return void
     */
     public function testGetUserPoints()
    {
        // login user
        $response = $this->call('POST', '/v1/login', 
                    [
                        "email" => "user1@mail.com",
                        "password" => "secret1"
                    ], [], [], []);

        $response = json_decode($response->getContent());
        $token = $response->data->access_token;

        $headers = [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ];

        $this->get('/v1/userpoint', $headers);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'response_code',
                'message',
                'data' => 
                [
                    '*' => [
                        'point_type_id',
                        'point_type_name',
                        'amount'
                    ]
                ]
            ]    
        );
    }
}
