<?php
// src/Service/AddressService.php
namespace App\Service;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class AddressService
{

    public function __construct(private LoggerInterface $logger, private EntityManagerInterface $em, private AddressRepository $repositoryAddress)
    {
    }

    public function saveAddress($contractAddress) {
            $address = $this->repositoryAddress->findOneBy(['address' => $contractAddress]);

            if (!empty($address)) {
                return $address->getId();
            }

            $address = new Address();
            $address->setAddress($contractAddress);
            $this->em->persist($address);
            $this->em->flush();

            return $address->getId();
    }

    public function getAddress($idAddress) {
        $address = $this->repositoryAddress->findOneBy(['id' => $idAddress]);

        if (empty($address)) {
            return null;
        }

        return $address->getAddress();
    }
}
