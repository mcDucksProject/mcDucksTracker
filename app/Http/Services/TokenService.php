<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Models\Token;

class TokenService
{
    /**
     * @throws SaveException
     */
    function create(string $name): Token
    {
        try {
            $token = new Token();
            $token->name = $name;
            $token->saveOrFail();
        } catch (\Throwable $e) {
            throw new SaveException();
        }
        return $token;
    }

    /**
     * @throws UpdateException
     */
    function update(int $tokenId, string $name = "")
    {
        try {
            $token = Token::findOrFail($tokenId);
            if ($name != "") {
                $token->name = $name;
            }
            $token->saveOrFail();
        } catch (\Throwable $e) {
            throw new UpdateException();
        }
        return $token;
    }

    /**
     * @throws DeleteException
     */
    function delete($tokenId)
    {
        try {
            $token = Token::findOrFail($tokenId);
            $token->deleteOrFail();
        } catch (\Throwable $e) {
            throw new DeleteException();
        }
    }
}
