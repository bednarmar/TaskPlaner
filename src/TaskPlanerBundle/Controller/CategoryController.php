<?php

namespace TaskPlanerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TaskPlanerBundle\Entity\Category;
use TaskPlanerBundle\Form\CategoryType;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{



    /**
     * @Route ("/create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category );
        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $category->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('taskplaner_category_showall');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/show/{id}")
     * @Template
     */
    public function showAction(Request $request, $id)
    {
// jeszcze nie pobralem danych do sesji wyzej, bo to objekt, tu ponizej nie moge sie odniesc do user bo to nie atrybut-tak mysle?
        $user = $this->getUser();
        $category = $this->getDoctrine()->getRepository('TaskPlanerBundle:Category')->find($id);

        if (!$category || $category->getUser() != $user) {
            throw $this->createNotFoundException('Category not found');
        }
        return ['category' => $category];

    }

    /**
     * @Route ("/showAll")
     * @Template
     */
    public function showAllAction()
    {

        //ponizej linia dobrze
        $categories = $this->getUser()->getCategories();


//ponizej dobrze
        return ['categories' => $categories];
    }

    /**
     * @Route ("/delete{id}")
     */
    public function deleteAction($id)
    {
        $category = $this->getDoctrine()->getRepository('TaskPlanerBundle:Category')->find($id);

        if (!$category){
            throw $this->createNotFoundException('Category not found');
        }
        $em = $this->getDoctrine()->getManager();
        $em ->remove($category);
        $em->flush();
        return $this->redirectToRoute("taskplaner_category_showall");
    }
}
