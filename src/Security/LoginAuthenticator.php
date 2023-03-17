<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public $userRepository;
    public $userPasswordHasherInterface;

    public function __construct(private UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $result = $this->userRepository->findByIdentifier($email);
        // dd($result);
 
        return new Passport(
            new UserBadge($email),
            new CustomCredentials(function($password, User $user) {
                // $password = $this->userPasswordHasherInterface->hashPassword(
                //     $user,
                //     'Pawel2001'
                // );

                // $this->userPasswordHasherInterface->

                // dd($user->getPassword(), $password);
                return ($this->userPasswordHasherInterface->isPasswordValid($user, $password));

            }, $password)
            // new PasswordCredentials($password,
            // [
            //     new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
            // ])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        dd('success');
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    // public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    // {
    //     dd($exception);
    // }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
