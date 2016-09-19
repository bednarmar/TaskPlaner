<?php

namespace TaskPlanerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TaskPlanerBundle\Entity\Task;
use TaskPlanerBundle\Form\TaskType;

/**
 * @Route ("/task")
 */
class TaskController extends Controller
{
    /**
     * @Route ("/create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(new TaskType($this->getUser()), $task);
        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('taskplaner_task_show',['id' => $task->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/show/{id}")
     * @Template()
     */
    public function showAction($id)

    {
        $task = $this->getDoctrine()->getRepository('TaskPlanerBundle:Task')->find($id);

        if (!$task){
            throw $this->createNotFoundException('Task not found');
        }

        return ['task' => $task];

    }

    /**
     * @Route("/showAll")
     * @Template()
     */
    public function showAllAction(Request $request)
    {
        if ($request->query->has('category')) {
            $category = $this
                ->getDoctrine()
                ->getRepository('TaskPlanerBundle:Category')
                ->findOneBy([
                    'id' => $request->query->get('category'),
                    'user' => $this->getUser()
                ]);

            if (!$category) {
                throw $this->createNotFoundException('Category not found');
            }

            $tasks = $category->getTasks();
        } else {
            $tasks = $this
                ->getDoctrine()
                ->getRepository('TaskPlanerBundle:Task')
                ->findByUser($this->getUser());
        }

        $categories = $this
            ->getDoctrine()
            ->getRepository('TaskPlanerBundle:Category')
            ->findByUser($this->getUser());

        return [
            'tasks' => $tasks,
            'categories' => $categories
        ];
    }

    /**
     * @Route ("/delete{id}")
     */
    public function deleteAction($id)
    {
        $task = $this->getDoctrine()->getRepository('TaskPlanerBundle:Task')->find($id);

        if (!$task){
            throw $this->createNotFoundException('Task not found');
        }
        $em = $this->getDoctrine()->getManager();
        $em ->remove($task);
        $em->flush();
        return $this->redirectToRoute("taskplaner_task_showall");
    }



}
