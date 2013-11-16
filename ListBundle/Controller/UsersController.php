<?php

namespace Users\Bundle\ListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Users\Bundle\ListBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/users")
     * @Template()
     */
    public function indexAction()
    {
		$repository = $this->getDoctrine()
			->getRepository('UsersListBundle:Users');
		
		$users = $repository->findAll();
		
        return array('users' => $users);
    }
	
    /**
     * @Route("/users/add")
     * @Template()
     */
    public function addAction(Request $request)
    {
		
		$user= new Users();
		$user->setName('Name');
		$user->setEmail('Email');
		$user->setPhone('Phone');
		
		$form=$this->createFormBuilder($user)
			->add('name')
			->add('email')
			->add('phone')
			->getForm();
			
		if ($request->getMethod()=='POST'){
			$form->bind($request);
			if ($form->isValid()){
				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();
				return $this->redirect($this->generateUrl('users_list_users_index'), 301);
			}
		} else {
			return $this->render('UsersListBundle:Users:add.html.twig',array(
				'form'=>$form->createView(),
			));
		}
    }
	
    /**
     * @Route("/users/edit/{id}", requirements={"id" = "\d+"}, defaults={"id" = 0})
     * @Template()
     */
    public function editAction($id, Request $request)
    {
		if ($id == 0){ // no user id entered
			return $this->redirect($this->generateUrl('users_list_users_index'), 301);
		}
		$user = $this->getDoctrine()
			->getRepository('UsersListBundle:Users')
			->find($id);
	
		if (!$user) {// no user in the system
			throw $this->createNotFoundException(
				'No user found for id '.$id
			);
		}
		
		$form=$this->createFormBuilder($user)
			->add('name')
			->add('email')
			->add('phone')
			->getForm();
			
		if ($request->getMethod()=='POST'){
			$form->bind($request);
			if ($form->isValid()){
				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();
				return $this->redirect($this->generateUrl('users_list_users_index'), 301);
			}
		} else {
			return $this->render('UsersListBundle:Users:edit.html.twig',array(
				'form'=>$form->createView(),
			));
		}
		
        return array('name' => $id);
    }
	
    /**
     * @Route("/users/delete/{id}", requirements={"id" = "\d+"}, defaults={"id" = 0})
     * @Template()
     */
    public function deleteAction($id)
    {
		if ($id == 0){ // no user id entered
			return $this->redirect($this->generateUrl('users_list_users_index'), 301);
		}
		
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('UsersListBundle:Users')->find($id);
	
		if (!$user) { // no user in the system
			throw $this->createNotFoundException(
				'No user found for id '.$id
			);
		} else {
			$em->remove($user);
			$em->flush();
			return $this->redirect($this->generateUrl('users_list_users_index'), 301);
		}
    }
}
