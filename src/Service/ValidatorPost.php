<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ValidatorPost
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;


    /**
     * ValidatorPost constructor.
     */
    public function __construct(ValidatorInterface $validator,SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }
    public function ValidatePost($utilisateur)
    {
        $errorString ='';
        $error = $this->validator->validate($utilisateur);
        if(isset($error) && $error >0){ $errorString = $this->serializer->serialize($error,'json');}
        return $errorString;
    }
}