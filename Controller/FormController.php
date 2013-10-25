<?php

namespace Nico\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nico\MailchimpBundle\Event\SaveFormEvent;
use Nico\MailchimpBundle\Event\MailchimpEvents;

use Symfony\Component\HttpFoundation\Request;
class FormController extends Controller
{

	protected $aliasHomepage = '_page_alias_homepage';
	public function saveAction(Request $request){
		$formBlock = $this->get('nico.form.block.form');
		if($request->getMethod()== 'POST'){
			$entityName = $formBlock->getEntity();
			$formTypeName = $formBlock->getFormType();
			$entity = new $entityName;
			$formType = new $formTypeName;
			$form = $this->createForm($formType, $entity);

			$form->handleRequest($request);
			$formArray = $request->request->all();
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

                $this->hasNewsletterService($formArray);
                $this->sendEmail($formBlock->getMailing(),$form->getData());
                $this->get('nico.error.message')->success($formBlock->getMessageSuccess());
            } else {
            	$this->get('nico.error.message')->error($formBlock->getMessageError());
            	$this->get('nico.error.message')->error($form->getErrors());
            }

            return $this->redirect($this->generateUrl($formBlock->getRedirection()));
        }
        return $this->redirect($this->generateUrl($this->aliasHomepage));
    }

    /**
    *   @param $emails string email(s) to send the data of the forms
    *   @param $data data to send in the email
    */
    private function sendEmail($mailing,$data){
        if(!empty($mailing)){
            $message = \Swift_Message::newInstance()
            ->setSubject($mailing->getSujet())
            ->setFrom($mailing->getEmetteur())
            ->setTo($mailing->getDestinataires())
            ->setContentType($mailing->getContentType())
            ->setBody(
                $this->renderView(
                    $mailing->getTemplate(),
                    array('data' => $data)
                    )
                )
            ;
            $this->get('mailer')->send($message);
            
        }
    }

    protected function hasNewsletterService($formData){
        foreach($formData as $field => $value){
            if(is_array($value)){
                $this->hasNewsletterService($value);
            }else{
                if(strpos(strtolower($field),'newslettermailchimp') !== false){
                    $event = new SaveFormEvent($formData);
                    $this->get('event_dispatcher')->dispatch(MailchimpEvents::onSaveToMailchimp,$event);
                }
            }
        }
    }
}
