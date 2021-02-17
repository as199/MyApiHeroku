<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 *@ApiResource(
* collectionOperations={
 *     "SendEmailApp":{
 *              "route_name"="Sending",
 *              "path":"/admin/mailer",
 *               "access_control"="(is_granted('ROLE_ADMIN') )",
 *               "deserialize" = false
 *              }
 *
 *      }
 *)
 */
class Admin extends Utilisateur
{
    
}
