<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $passwordEncoder;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }
    public function persist($data, array $context = [])
    {
        $data->setPassword($this->passwordEncoder->encodePassword(
            $data,
            $data->getPassword()
        ));
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}