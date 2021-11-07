<?php

namespace App\Test\TestCase\Action\User;

use App\Domain\User\Type\UserRoleType;
use App\Test\Traits\AppTestTrait;
use Cake\Chronos\Chronos;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserCreateAction
 */
class UserCreateActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        Chronos::setTestNow('2021-01-01 00:00:00');

        $request = $this->createJsonRequest(
            'POST',
            '/api/v1/users',
            [
                'username' => 'admin',
                'password' => '12345678',
                'email' => 'mail@example.com',
                'first_name' => 'Sally',
                'last_name' => 'Doe',
                'user_role_id' => UserRoleType::ROLE_USER,
                'locale' => 'de_DE',
                'enabled' => true,
            ]
        );
        $request = $this->withHttpBasicAuth($request);

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['user_id' => 1], $response);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('User created successfully'));

        // Check database
        $this->assertTableRowCount(1, 'users');

        $expected = [
            'user_id' => '1',
            'username' => 'admin',
            'email' => 'mail@example.com',
            'first_name' => 'Sally',
            'last_name' => 'Doe',
            'user_role_id' => '2',
            'locale' => 'de_DE',
            'enabled' => '1',
        ];

        $this->assertTableRow($expected, 'users', 1);
        $this->assertTableRowValue('1', 'users', 1, 'id');

        // Password
        $password = $this->getTableRowById('users', 1)['password'];
        $this->assertStringStartsWith('$2y$10$', $password);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUserValidation(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/api/v1/users',
            [
                'username' => '',
                'password' => '1234',
                'email' => 'mail',
                'user_role_id' => 99,
                'locale' => 'aa_aa',
                'enabled' => 'a',
            ]
        );
        $request = $this->withHttpBasicAuth($request);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(
            [
                'error' => [
                    'message' => 'Please check your input',
                    'code' => 422,
                    'details' => [
                        0 => [
                            'message' => 'Input required',
                            'field' => 'username',
                        ],
                        1 => [
                            'message' => 'Too short',
                            'field' => 'password',
                        ],
                        2 => [
                            'message' => 'Input required',
                            'field' => 'email',
                        ],
                        3 => [
                            'message' => 'Invalid',
                            'field' => 'user_role_id',
                        ],
                        4 => [
                            'message' => 'Invalid',
                            'field' => 'locale',
                        ],
                        5 => [
                            'message' => 'Invalid',
                            'field' => 'enabled',
                        ],
                    ],
                ],
            ],
            $response
        );
    }
}
