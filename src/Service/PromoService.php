<?php


namespace App\Service;


use ApiPlatform\Core\Api\IriConverterInterface;
use App\Repository\PromoRepository;
use Doctrine\ORM\QueryBuilder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Component\Serializer\SerializerInterface;

class PromoService
{
    /**
     * @var PromoRepository
     */
    private PromoRepository $promoRepository;


    /**
     * PromoService constructor.
     */
    public function __construct(IriConverterInterface $iriConverter,SerializerInterface $serializer,PromoRepository $promoRepository)
    {
        $this->promoRepository = $promoRepository;
    }

    /**
     * @param null $id
     * @return QueryBuilder
     */
    public function getPromoPrincipale($id= null): QueryBuilder
    {

        $dt= $this->promoRepository->getPromoGroupePrincipal($id);
        return $dt;
    }
    public function getApprenantAttente(): QueryBuilder
    {
        return $this->promoRepository->findApprenantAttente();
    }

    public function getApprenantPromoAttente(): QueryBuilder
    {
        return $this->promoRepository->findApprenantPromoAttente();
    }

    public function getFormateur(): QueryBuilder
    {
        return $this->promoRepository->findFormateur();
    }

    /**
     * permet upload files et le mettre en array
     * @param $file
     * @return array|null
     */
    public static function uploadExcel($file): ?array
    {
        if($file){
            // je recuperer le type de l'extension du fichier
            $doc = IOFactory::identify($file);

            try {

            $docRader = IOFactory::createReader($doc);
            } catch (Exception $e) {
            }
            if (!empty($docRader)) {
                $spreadSheet = $docRader->load($file);
            }
            if (isset($spreadSheet)) {
                return $spreadSheet->getActiveSheet()->toArray();
            }

        }

            return null;

    }

    /**
     * permet de permet recuper les infos du fichier excel
     * @param $data
     * @return array
     */
    public static  function toArray ($data): array
    {
        $collection = [];
        $array = [];
        for ($name =0; $name < count($data); $name++){
            foreach ($data[0] as $key => $keyArray){
                if (!empty($data[$name+1])){
                    $array[$keyArray]= $data[$name+1][$key];
                }
            }
            array_push($collection,$array);
        }
        array_pop($collection);
        return $collection;
    }
}