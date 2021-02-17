<?php


namespace App\Event;


use App\Repository\UtilisateurRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    /**
     * @var UtilisateurRepository
     */
    private UtilisateurRepository $user;

    public function __construct(UtilisateurRepository $user){
        $this->user = $user;
    }
    public function updateJwtData(JWTCreatedEvent $event)
    {

        // On enrichit le data du Token
        $data = $event->getData();
        $res = $this->user->findBy(['username'=>$data['username']]);
        $data['archived'] =$res[0]->getStatus();
        $data['id'] =  $res[0]->getId();
        $data['nomComplet'] =  $res[0]->getNomComplete();

        $event->setData($data);
    }
}
