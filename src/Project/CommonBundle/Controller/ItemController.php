<?php

namespace Project\CommonBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Project\CommonBundle\Entity\Item;
use Project\CommonBundle\Entity\ItemRepository;
class ItemController extends Controller
{
    public function itemsAction()
    {
        return $this->render('ProjectCommonBundle:Item:items.html.twig');
    }

    public function itemsApiAction()
    {
        /** @var  $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var  $itemRepo ItemRepository */
        $itemRepo = $em->getRepository('ProjectCommonBundle:Item');
        $items = $itemRepo->findAllData();

        $response = new Response(json_encode($items));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function addItemApiAction(Request $request){

        $data = json_decode($request->getContent(), true);
        $itemName = $data['name'];
        $itemPrice = $data['price'];

        $item = new Item();
        $item->setName($itemName);
        $item->setPrice($itemPrice);

        /** @var  $em EntityManager */
        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $em->refresh($item);

        /** @var  $itemRepo ItemRepository */
        $itemRepo = $em->getRepository('ProjectCommonBundle:Item');
        $addedItem = $itemRepo->findData($item->getId());

        $response = new Response(json_encode($addedItem,1));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function removeItemAction($id){
        /** @var  $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('ProjectCommonBundle:Item')->find($id);

        if(!$item){
            throw $this->createNotFoundException('The product does not exist');
        }

        $em->remove($item);
        $em->flush();

        $response = new Response('success', Response::HTTP_OK);
        return $response;
    }
}
