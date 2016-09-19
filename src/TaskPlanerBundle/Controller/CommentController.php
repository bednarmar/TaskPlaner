<?php

namespace TaskPlanerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TaskPlanerBundle\Entity\Comment;
use TaskPlanerBundle\Form\CommentType;

/**
 * @Route ("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route ("/create/{taskId}")
     * @Template()
     */
    public function createAction(Request $request, $taskId)
    {

        $task = $this->getDoctrine()->getRepository('TaskPlanerBundle:Task')->find($taskId);
        $comment = new Comment();

        $form = $this->createForm(new CommentType(), $comment);
        $form->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $comment->setTask($task);
            $task-> addComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('taskplaner_comment_show', ['id' => $comment->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
    * @Route("/show/{id}")
    * @Template()
    */
    public function showAction($id)

    {
        $comment = $this->getDoctrine()->getRepository('TaskPlanerBundle:Comment')->find($id);

        if (!$comment){
            throw $this->createNotFoundException('Task not found');
        }
        return ['comment' => $comment];

    }

    /**
     * @Route ("/showAll")
     * @Template
     */
     public function showAllAction()
     {

         $comments = $this->getTask()->getComments();
         return ['comments'=>$comments];
     }

    /**
     * @Route ("/delete/{id}")
     */
    public function deleteAction($id)
    {
        $comment = $this->getDoctrine()->getRepository('TaskPlanerBundle:Comment')->find($id);

        if (!$comment){
            throw $this->createNotFoundException('Comment not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em -> remove($comment);
        $em -> flush();

        return $this->redirectToRoute('taskplaner_task_showall');
    }
}
