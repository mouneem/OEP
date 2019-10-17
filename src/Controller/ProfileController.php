<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Security $security)
    {
        $user = $security->getUser();
        $profile = $user->getProfile();
        return $this->render('profile/profile.html.twig', [
            'user_profile' => $profile,
            'isUpdated' => 0,
        ]);
    }


    /**
     * @Route("/profile/update", name="update")
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Security $security)
    {
        $user = $security->getUser();
        $profile = $user->getProfile();

        $profile->setFirstname($_POST['firstname']);
        $profile->setSecondname($_POST['secondname']);
        $profile->setGender($_POST['gender']);
        $profile->setOccupation($_POST['occupation']);
        $profile->setAffiliation($_POST['affiliation']);
        $profile->setCountry($_POST['country']);
        $profile->setCity($_POST['city']);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($profile);
        $entityManager->flush();

        return $this->render('profile/profile.html.twig', [
            'user_profile' => $profile,
            'isUpdated' => 1,
        ]);
    }


    /**
     * @Route("/accounts/update", name="accountsupdate")
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountsupdate(Security $security)
    {
        $user = $security->getUser();
        $profile = $user->getProfile();


        $profile->setWebsite($_POST['Website']);
        $profile->setOrcid($_POST['Orcid']);
        $profile->setTwitter($_POST['Twitter']);
        $profile->setLinkedIn($_POST['LinkedIn']);
        $profile->setFacebook($_POST['Facebook']);
        $profile->setInstagram($_POST['Instagram']);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($profile);
        $entityManager->flush();

        return $this->render('profile/profile.html.twig', [
            'user_profile' => $profile,
            'isUpdated' => 1,
        ]);
    }
}
