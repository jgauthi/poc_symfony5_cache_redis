<?php
namespace App\Controller;

use App\Entity\Dossier;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class DossierController extends AbstractController
{
    public function __construct(private DossierRepository $dossierRepository)
    {
    }

    #[Route('/', name: 'dossierList')]
    public function dossierList(Request $request, PaginatorInterface $paginator, CacheInterface $cache): Response
    {
        $currentPage = $request->query->getInt('page', 1);

//        $redis = new Redis;
//        $redis->connect(host: 'cache', port: 6379, timeout: 2.5);

        // Not working with Knp Pagination, error with serialization
//        $dossierList = $cache->get('home_dossier_list_p'.$currentPage, function () use ($currentPage, $paginator) {
//            sleep(1); // add delay for test Redis on prod
//            $query = $this->dossierRepository->findVisibleQuery();
//            return $paginator->paginate($query, $currentPage, 6);
//        });

        $query = $this->dossierRepository->findVisibleQuery();
        $dossierList = $paginator->paginate($query, $currentPage, 6);

        return $this->render('dossierList.html.twig', [
            'dossierList' => $dossierList,
        ]);
    }

    #[Route('/dossier/{id}', name: 'dossierItem', requirements: ['id' => '\d+'])]
    public function dossierItem(int $id, CacheInterface $cache, EntityManagerInterface $entityManager): Response
    {
        // $cache->delete('dossier'.$id); // debug
        $dossier = $cache->get('dossier'.$id, function () use ($id): ?Dossier {
            sleep(4);

            return $this->dossierRepository->find($id);
        });

        if (empty($dossier)) {
            throw $this->createNotFoundException("Dossier $id not found.");
        }

        // https://stackoverflow.com/questions/45034146/symfony-cache-component-and-lazy-loading
        $dossier = $entityManager->merge($dossier);

        return $this->render('dossierItem.html.twig', [
            'dossier' => $dossier,
        ]);
    }
}
