<?php


namespace App\dataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Promo;
use App\Service\PromoService;

class PromoCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var PromoService
     */
    private PromoService $promoService;

    /**
     * PromoDataProvider constructor.
     */
    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {

        if($operationName !=="get"){
            return Promo::class == $resourceClass;
        }else{
            return false;
        }
    }
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
      if ($operationName == "get_groupe_principale"){
        return $this->promoService->getPromoPrincipale();
      }
      if ($operationName =="get_apprenant_attente"){
          return $this->promoService->getApprenantAttente();
      }
      return new Promo();
    }


}