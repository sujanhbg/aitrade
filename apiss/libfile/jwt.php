<?php

declare(strict_types=1);

namespace kring\auth;

use DateTimeImmutable;
use kring\database\dbal;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;

class jwt
{

    function dbal()
    {
        return new dbal();
    }

    function insert_jwt($jwt, $userID)
    {
        //$sql = "INSERT INTO `user_jwt`(`token`, `issue_time`, `user_id`, `last_check_time`) VALUES (\"{$jwt}\",NOW(),\"{$userID}\",NOW())";
        //$this->dbal()->query_exc($sql);
    }

    function issue_jwt($userID, $userRole = 44)
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText('2T9/JxjJHNuiTdMIzui7BAtsltse/YElK/PvcladXZY=');

        $now = new DateTimeImmutable();
        $token = $tokenBuilder
            ->issuedBy('https://api.trade.sujan.pw')
            ->permittedFor('http://localhost')
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+12 hour'))
            ->withClaim('uid', $userID)
            ->withClaim('role', $userRole)
            ->getToken($algorithm, $signingKey);
        $this->insert_jwt($token->toString(), $userID);
        return $token->toString();
    }

    function is_valid($planeToken)
    {
        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse(
            $planeToken
        );

        $validator = new Validator();

        try {
            $validator->assert($token, new Constraint\StrictValidAt(
                new FrozenClock(new DateTimeImmutable())
            ));
            $return = 0;
            //$return=false;
        } catch (RequiredConstraintsViolated $e) {
            $return = $e->getMessage();
            //print_r($e->violations()[0]['message']);
        }
        return $return;
    }

    function get_uid()
    {
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        }
        $jwt = $matches[1];
        if (!$jwt) {
            // No token was able to be extracted from the authorization header
            header('HTTP/1.0 400 Bad Request');
            exit;
        }

        if ($this->is_valid($jwt) == 0) {
            $parser = new Parser(new JoseEncoder());
            $parser = new Parser(new JoseEncoder());
            $key = InMemory::plainText('2T9/JxjJHNuiTdMIzui7BAtsltse/YElK/PvcladXZY=');
            $token = (new JwtFacade())->parse(
                $jwt,
                new Constraint\SignedWith(new Sha256(), $key),
                new Constraint\StrictValidAt(
                    new FrozenClock(new DateTimeImmutable())
                )
            );
            $data['status'] = "success";
            $data['uid'] = $token->claims()->get('uid');
            $data['role'] = $token->claims()->get('role');
        } else {
            $data['status'] = "error";
            $data['msg'] = "You are not logged in";
        }

        return $data;
    }

    function juid()
    {
        $jwtdt = $this->get_uid();
        if ($jwtdt['status'] == 'success') {
            $uid = $jwtdt['uid'];
        } else {
            $uid = null;
        }
        return $uid;
    }
}
