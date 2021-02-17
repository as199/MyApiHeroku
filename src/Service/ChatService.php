<?php


namespace App\Service;


use App\Entity\Chat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ChatService
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {

        $this->serializer = $serializer;
    }
    public function NewChat(Request $request){
        $chat = $request->request->all();
        $uploadedFile = $request->files->get('piecesJointes');


        if($uploadedFile){
            $file = $uploadedFile->getRealPath();
            $piecsjointes = fopen($file,'r+');
            $chat['piecesJointes']=$piecsjointes;
        }

        return $chat;
    }

}