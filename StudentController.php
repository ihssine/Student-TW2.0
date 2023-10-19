<?php

namespace App\Controller;
use App\Repository\StudentRepository;
use App\Entity\Student;
use App\Repository\ClassroomRepository;
use App\Form\StudentType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $repo): Response
    {
        $result=$repo->findAll();
        return $this-> render('student/test.html.twig', [
            'response'=>$result,
        ]);
    }
    #[Route('/add', name: 'add')]
    public function add(ClassroomRepository $repo , ManagerRegistry $mr ): Response
    {
        $s=new Student();
        $s->setName('test');
        $s->setEmail('test@gmail.com');
        $s->setage('28');
        $em=$mr->getManager();
        $em->persist($S);
        $em->flush();
        return $this->redirectToRoute('fetch');
    }
    #[Route('/addF', name: 'addF')]
    public function addF(ClassroomRepository $repo , ManagerRegistry $mr , Request $req): Response
    {
        $S=new Student();
        $form=$this->createForm(StudentType::class,$S);
        $form->handleRequest($req);
        if($form->isSubmitted()){
        $em=$mr->getManager();
        $em->persist($S);
        $em->flush();
        return $this->redirectToRoute('fetch');
        }
        return $this->render('student/add.html.twig',['f'=>$form->createView()]);
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(StudentRepository $repo , $id ,ManagerRegistry $mr): Response
    {
        $student=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($student);
        $em->flush();
        return new Response ('removed');
    }
    #[Route('/update/{id}', name: 'update')]
    public function update($id, StudentRepository $repo, ManagerRegistry $mr, Request $req): Response
    {
        $student = $repo->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);

    if ($form->isSubmitted()) {
        $em = $mr->getManager();
        $em->flush();
        return $this->redirectToRoute('fetch');
    }

    return $this->render('student/update.html.twig', ['f' => $form->createView()]);
}

}
