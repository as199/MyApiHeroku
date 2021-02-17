<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use App\Entity\ProfilSorti;
use App\Repository\UtilisageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProfilSortiDataPersister  implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var UtilisateurRepository
     */
    private UtilisateurRepository $user;


    /**
     * ProfilDataPersister constructor.
     */
    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $user)
    {
        $this->manager=$manager;
        $this->user=$user;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ProfilSorti;
    }

    public function persist($data, array $context = [])
    {
       //dd($data);
        $data->setLibelle($data->getLibelle());
        $data->setStatus(false);
        $this->manager->persist($data);
        $this->manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setStatus(true);
        $this->manager->persist($data);
        $this->manager->flush();

        return new JsonResponse("Archivage successfully!",200,[],true);

    }
}