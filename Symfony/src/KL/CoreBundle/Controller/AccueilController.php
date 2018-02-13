<?php

namespace KL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('KLCoreBundle:Accueil:index.html.twig');
    }

    public function connectAction()
    {
        return $this->render('KLCoreBundle:Accueil:connect.html.twig');
    }

    public function inscriptionAction()
    {
        return $this->render('KLCoreBundle:Accueil:inscription.html.twig');
    }

    public function contactAction(Request $request)
    {
        $form = $this->createForm('KL\RestaurationBundle\Form\ContactType',null,array(
            'action' => $this->generateUrl('kl_core_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                if($this->sendEmail($form->getData())){
                    $this->addFlash(
                        'success',
                        'Votre message a bien été envoyé !'
                    );

                    return $this->redirectToRoute('kl_core_contact');
                }else{
                    $this->addFlash(
                        'danger',
                        'Une erreur s\'est passée veuillez réessayer !'
                    );

                    return $this->redirectToRoute('kl_core_contact');
                }
            }
        }

        return $this->render('KLCoreBundle:Accueil:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function sendEmail($data){
        $myappContactMail = 'exemple@gmail.com';
        $myappContactPassword = 'mdpDeLemail';

        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("[K&LRestauration] ". $data["subject"])
        ->setFrom(array($myappContactMail => "Message de ".$data["name"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody($data["message"]." <br> Contact Mail :".$data["email"], 'text/html');

        return $mailer->send($message);
    }

}
