<?php

namespace App\Controller;

use App\Entity\Course;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PadController extends AbstractController
{
    /**
     * @Route("/pad", name="pad")
     */
    public function pad(Security $security)
    {
        $link = $_POST['link'];
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $link]);
        if ($course->getCreatedBy()->getId() == $user->getId() or in_array($user->getId(), (array)$course->getStudentsList())) {
            $pad = $course->getPad();
            $pad->setContent($_POST['content']);
            $u = $user;
            $u->setLastSeen(new DateTime());
            $em->persist($user);
            $em->persist($pad);
            $em->flush();
            return $this->redirect('/c/'.$link);
        }
        return False;
    }
    /**
     * @Route("/help", name="help")
     */
    public function help(Security $security)
    {
        $link = $_POST['link'];
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $link]);
        if ($course->getCreatedBy()->getId() == $user->getId() or in_array($user->getId(), (array)$course->getStudentsList())) {
            $help = $course->getHelpsByUser($user);
            $help->setIsNeeded(!$help->getIsNeeded());
            $user->setLastSeen(new DateTime());
            $em->persist($user);
            $em->persist($help);
            $em->flush();
            return $this->redirect('/c/'.$link);
        }
        return False;
    }



}
