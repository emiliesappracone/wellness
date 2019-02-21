<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Provider;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{provider}", name="front.comment.add")
     */
    public function comment(Request $request, $provider)
    {
        $user = $this->getUser();
        $type = strtolower($user->getClass());
        if (is_object($user) && $type == 'surfer') {
            $provider = $this->getDoctrine()->getRepository(Provider::class)->find($provider);
            $content = $request->request->get('content');
            $comment = new Comment();
            $comment->setContent($content);
            $comment->setCreatedAt(new \DateTime());
            $comment->setProvider($provider);
            $comment->setSurfer($user);
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('provider.one', ['slug' => $provider->getSlug()]);
    }

    /**
     * @Route("/admin/comments/add", name="admin.comment.add")
     */
    public function add(Request $request)
    {
        $comment = new Comment('');
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // persist in db
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            // redirect to route with message
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Création réussie');
            return $this->redirectToRoute("admin.comments");
        }
        return $this->render('/admin/comments/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'comments',
            'action' => 'Créer'
        ]);
    }

    /**
     * @Route("/admin/comment/edit/{id}", name="admin.comment.update")
     */
    public function edit(Request $request, $id)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // persist in db
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            // redirect to route with message
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Modification réussie');
            return $this->redirectToRoute("admin.comments");
        }
        return $this->render('/admin/comments/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'comments',
            'action' => 'Updater'
        ]);
    }

    /**
     * @Route("/admin/comment/delete/{id}", name="admin.comment.delete", methods="DELETE")
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Comment $comment)
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($comment);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Commentaire supprimé');
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Commentaire non supprimé');
        }
        return $this->redirectToRoute('admin.comments');
    }
}
