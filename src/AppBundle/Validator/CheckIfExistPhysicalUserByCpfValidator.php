<?php

namespace AppBundle\Validator;

use AppBundle\Exceptions\AbstractException;
use AppBundle\Exceptions\Factories\ExceptionFactory;
use AppBundle\Exceptions\InvalidUserException;
use AppBundle\Repository\PersonUserRepository;

class CheckIfExistPhysicalUserByCpfValidator extends CheckIfExistUserValidator
{
    /**
     * @var string
     */
    private $cpf;

    /**
     * @param string $columnName
     * @param string $email
     * @param int|null $ignoreUserId
     * @param PersonUserRepository $repository
     */
    public function __construct(string $columnName, string $email, ?int $ignoreUserId, PersonUserRepository $repository)
    {
        parent::__construct($columnName, $ignoreUserId, $repository);
        $this->cpf = $email;
    }

    /**
     * @return bool
     * @throws AbstractException
     */
    public function check(): bool
    {
        $user = $this->repository->findOneBy([$this->columnName => $this->cpf]);

        if (null !== $user && $user->getId() !== $this->ignoreUserId) {
            throw ExceptionFactory::create(
                InvalidUserException::class,
                "Já existe um usuário com este CPF ({$this->cpf}) cadastrado!"
            );
        }

        return $this->checkNext();
    }

    /**
     * @param string $cpf
     * @return CheckIfExistPhysicalUserByCpfValidator
     */
    public function setCpf(string $cpf): CheckIfExistPhysicalUserByCpfValidator
    {
        $this->cpf = $cpf;
        return $this;
    }
}