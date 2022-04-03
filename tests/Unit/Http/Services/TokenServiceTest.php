<?php

namespace Http\Services;

use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Http\Services\TokenService;
use App\Models\Token;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{
    const TOKEN_NAME = 'TEST';
    const CHANGED_NAME = "CHANGED";

    private static function getToken(): Token
    {
        return new Token(['name' => self::TOKEN_NAME]);
    }

    /**
     * @throws SaveException
     */
    public function testShouldCreateToken()
    {
        $tokenModel = Mockery::mock('overload:' . Token::class);
        $tokenModel->shouldReceive('saveOrFail')
            ->andReturnTrue();
        $tokenService = new TokenService();
        $token = $tokenService->create(self::TOKEN_NAME);
        $this->assertEquals(self::TOKEN_NAME, $token->name);
    }

    public function testShouldFailToCreateToken()
    {
        $tokenModel = Mockery::mock('overload:' . Token::class);

        $tokenModel->shouldReceive('saveOrFail')
            ->andThrow(new ModelNotFoundException());
        $tokenService = new TokenService();
        $this->expectException(SaveException::class);
        $tokenService->create(self::TOKEN_NAME);
    }

    /**
     * @throws UpdateException
     */
    public function testShouldUpdateToken()
    {

        $tokenModel = Mockery::mock('overload:' . Token::class)->shouldIgnoreMissing();
        $tokenModel->shouldReceive('saveOrFail')
            ->withNoArgs()
            ->andReturnTrue();
        $tokenModel->shouldReceive('findOrFail')
            ->with(1)
            ->andReturn(self::getToken());
        $tokenService = new TokenService();
        $updatedToken = $tokenService->update(1, self::CHANGED_NAME);
        $this->assertEquals(self::CHANGED_NAME, $updatedToken->name);
    }
}
