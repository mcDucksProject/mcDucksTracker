<?php

namespace App\Http\Services;

use App\Exceptions\DeleteException;
use App\Exceptions\SaveException;
use App\Exceptions\UpdateException;
use App\Models\Token;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    function update(int $tokenId, string $name = ""): Token
    {
        try {
            $token = $this->getById($tokenId);
            if ($name != "") {
                $token->name = $name;
            }
            $token->saveOrFail();
        } catch (\Throwable $e) {
            throw new UpdateException("There was an error updating. " . $e->getMessage());
        }
        return $token;
    }

    /**
     * @throws DeleteException
     */
    function delete($tokenId): void
    {
        try {
            $token = $this->getById($tokenId);
            $token->deleteOrFail();
        } catch (\Throwable $e) {
            throw new DeleteException();
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    function getById($tokenId): Token
    {
        return Token::findOrFail($tokenId);
    }

    /**
     * @throws ModelNotFoundException
     */
    function getByName($name): Token
    {
        return Token::whereName($name)->firstOrFail();
    }

    function getAll(): Collection
    {
        return Token::all();
    }
}
