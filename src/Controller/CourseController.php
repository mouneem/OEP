<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Help;
use App\Entity\JointFile;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormTypeInterface;
use App\Form\JointfileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


class CourseController extends AbstractController
{
    /**
     * @Route("/course/create", name="coursecreate")
     */
    public function coursecreate()
    {
        return $this->render('course/create.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/course/new", name="coursenew")
     * @param Security $security
     * @return Response
     */
    public function coursenew(Security $security)
    {
        $user = $security->getUser();
        $course = new Course($user);

        $course->setTitle($_POST["Title"]);
        $course->setCategory($_POST["Category"]);
        $course->setDescription($_POST["Description"]);
        $course->setCourseLocation($_POST["Location"]);

        $course->setisVisible($_POST["isVisible"]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($course);
        $entityManager->flush();

        return $this->render('default/index.html.twig', [
            'isUpdated' => 1,
        ]);
    }

    /**
     * @Route("/course/update", name="courseedit")
     * @param Security $security
     * @return Response
     */
    public function courseedit(Security $security)
    {
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $_POST['CourseId']]);
        if ($course->getCreatedBy()->getId() == $user->getId()) {
            $course->setTitle($_POST["Title"]);
            $course->setCategory($_POST["Category"]);
            $course->setDescription($_POST["Description"]);
            $course->setCourseLocation($_POST["Location"]);
            $course->setisVisible($_POST["isVisible"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
            return $this->render('default/index.html.twig', [
                'isUpdated' => 1,
                'Message' => 'Course Updated with success !',
            ]);
        }
        return $this->render('default/index.html.twig', [
            'isUpdated' => 1,
        ]);
    }

    /**
     * @Route("/courses", name="coursemy")
     * @param Security $security
     * @return Response
     */
    public function coursemy(Security $security)
    {
        $user = $security->getUser();

        $courses = $user->getCoursesCreated();
        $coursesEnrolled = $user->getCourseEnrolled();

        return $this->render('course/courses.html.twig', [
            'CreatedCourses' => $courses,
            'EnrolledCourses' => $coursesEnrolled,
        ]);
    }


    /**
     * @Route("/hide/course/{courseId}", name="hidecourse")
     * @param Security $security
     * @param int $courseId
     * @return Response
     */
    public function hidecourse(Security $security, int $courseId)
    {
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $Course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $courseId]);
        if ($Course->getCreatedBy()->getId() == $user->getId()) {
            $Course->setIsVisible(!$Course->getIsVisible());

            $em->persist($Course);
            $em->flush();
            return $this->redirect('/courses');
        }
        return $this->redirect('/');
    }

    /**
     * @Route("/course/edit/{courseLink}", name="editCourse")
     * @param Security $security
     * @param $courseLink
     * @return Response
     */
    public function editCourse(Security $security, $courseLink)
    {
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $Course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $courseLink]);
        if ($Course->getCreatedBy()->getId() == $user->getId()) {
            return $this->render('course/edit.html.twig', [
                'Course' => $Course,
            ]);
        }
        return $this->redirect('/');
    }

    /**
     * @Route("/c/{link}", name="courseInterface")
     * @param Security $security
     * @param $link
     * @return Response
     * @throws \Exception
     */
    public function courseInterface(Security $security, $link)
    {
        if ($security->getUser()) {
            $user = $security->getUser();
            $em = $this->getDoctrine()->getManager();
            $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $link]);
            if ($course->getCreatedBy()->getId() == $user->getId() or in_array($user->getId(), (array)$course->getStudentsList())) {
                $u = $user;
                $u->setLastSeen(new DateTime());
                $em->persist($u);
                $em->flush();

                $file = new JointFile($course, $U = $user);
                $form = $this->createForm(JointfileType::class, $file);

                return $this->render('course/interface.html.twig', [
                    'Course' => $course,
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('course/join.html.twig', [
            'l' => $course->getLink(),
        ]);
    }

    /**
     * @Route("/join/{link}", name="join")
     * @param Security $security
     * @return Response
     * @throws \Exception
     */
    public function join(Security $security, $link)
    {
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $link]);
        $course->addStudent($user);
        $h = new Help($Course = $course ,$User = $user ) ;
        $u = $user;
        $u->setLastSeen(new DateTime());
        $em->persist($h);
        $em->persist($u);
        $em->persist($course);
        $em->flush();


        return $this->redirect('/c/' . $link);

    }
}
