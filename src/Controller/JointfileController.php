<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\JointFile;
use App\Form\JointfileType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class JointfileController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     * @param Request $request
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Security $security)
    {

        $link = $_POST["link"];
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['link' => $link]);
        $user = $security->getUser();
        $file = new JointFile($course, $user);

        $form = $this->createForm(JointfileType::class, $file);
        $form->handleRequest($request);

        $form = $this->createForm(JointfileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $brochureFile = $form['file']->getData();

            // this condition is needed because the 'brochure' field is not required
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('files_repository'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $file->setFileLocation($newFilename);
                $file->setTitle($form['Title']->getData());
            }

                $em = $this->getDoctrine()->getManager();

                $em->persist($file);
                $em->flush();

            return $this->redirect('/c/'.$course->getLink());
        }

        return $this->render('jointfile/index.html.twig', [
            'controller_name' => 'JointfileController',
        ]);
    }


    /**
     * @Route("/download/{link}", name="download")
     * @param Security $security
     * @param $link
     * @return BinaryFileResponse
     */
    public function download(Security $security, $link)
    {
        $user = $security->getUser();
        $em = $this->getDoctrine()->getManager();
        $course = $this->getDoctrine()->getRepository(JointFile::class)->findOneBy(['id' => $link]);
        $course->setDownloadCount($course->getDownloadCount() + 1);
        $user->setLastSeen(new DateTime());
        $em->persist($user);
        $em->persist($course);
        $em->flush();
        $response = new BinaryFileResponse('/data/files/'.$course->getFileLocation());
        return($response);
    }
}
