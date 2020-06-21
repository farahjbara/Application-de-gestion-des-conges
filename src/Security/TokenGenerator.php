<?php

namespace App\Security;

class TokenGenerator
{
	private const ALPHABET = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	public function createRandomSecureToken(int $length = 62): string
	{
		$token = "";
		$charactersLength = strlen(self::ALPHABET);

		for ($i = 0; $i < $length; $i++) {
			$token .= self::ALPHABET[rand(0, $charactersLength - 1)];
		}

		return $token;

	}
}