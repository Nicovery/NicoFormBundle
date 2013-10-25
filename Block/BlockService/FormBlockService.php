<?php

namespace Nico\FormBundle\Block\BlockService;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ExecutionContextInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use Nico\FormBundle\Block\BlockService\Settings;
class FormBlockService extends BaseBlockService
{
    protected $entity;
    protected $formType;
    protected $redirection;
    protected $template;
    protected $formName;
    protected $container;
    protected $session;
    protected $mailing;
    protected $messageError;
    protected $messageSuccess;

    protected $settingsObj;
    public function __construct($name, EngineInterface $templating,ContainerInterface $container)
    {
        parent::__construct($name, $templating);
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->settingsObj = new Settings();
    }


    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

        $repoMailing = $this->container->get('doctrine')
        ->getManager() 
        ->getRepository('NicoFormBundle:Mailing');
        $mailingChoices = $repoMailing->choices();

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('formName', 'text', array()),
                array('entity', 'text', array()),
                array('formType', 'text', array()),
                array('redirection', 'text', array()),
                array('template', 'text', array()),
                array('messageSuccess', 'text', array()),
                array('messageError', 'text', array()),
                array('mailing','choice',array(
                    'required'=>false,
                    'choices'=>$mailingChoices,
                    ))

                ),
            ));

    }
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
     $settings =  $blockContext->getSettings();
       //when you create a controller you need to bind a ContainerAware Object
     $controller = new Controller();
     $controller->setContainer($this->container);

     $entity = new $settings['entity'];
     $formType = new $settings['formType'];
     $redirection = $settings['redirection'];
     $form = $controller->createForm($formType, $entity);

     $this->saveSettingsObj($settings);

     return $this->renderResponse($blockContext->getTemplate(), array(
        'block'     => $blockContext->getBlock(),
        'settings'  => $blockContext->getSettings(),
        'form'      => $form->createView(),
        ), $response);
    }

    public function saveSettingsObj($settings){
        $this->settingsObj->link($settings);

        $serializer = $this->container->get('jms_serializer');
        $serializedSettingsObj = $serializer->serialize($this->settingsObj,'json') ;

        $this->session->set('_form'.$this->formName.'_settings',$serializedSettingsObj);
    }

    protected function retrieveSettingsObj(){
        $serializedSettingsObj = $this->session->get('_form'.$this->formName.'_settings');
        $serializer = $this->container->get('jms_serializer');
        return $serializer->deserialize($serializedSettingsObj, 'Nico\FormBundle\Block\BlockService\Settings' ,'json') ;
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
        $settings =  $blockContext->getSettings();


        $errorElement
        ->with('settings.formName')
        ->assertNotNull()
        ->assertNotBlank()
        ->end()
        ->with('settings.entity')
        ->assertNotNull()
        ->assertNotBlank()
        ->end()
        ->with('settings.formType')
        ->assertNotNull()
        ->assertNotBlank()
        ->end()
        ->with('settings.redirection')
        ->assertNotNull()
        ->assertNotBlank()
        ->end()
        ->with('settings.template')
        ->assertNotNull()
        ->assertNotBlank()
        ->end();
        if(!class_exists($settings['entity'])){
            $errorElement->addViolation('Entité n\'existe pas')->end();
        }
        if(!class_exists($settings['formType'])){
            $errorElement->addViolation('Form type n\'existe pas')->end();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Form (nico)';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'formName'  => 'Form Name',
            'entity'  => 'Entity',
            'formType'=> 'FormType',
            'redirection'=>'redirection',
            'template' => 'NicoFormBundle:Block:default_form.html.twig',
            'mailing'=>'',
            'messageSuccess'=>'Votre message a bien été envoyé',
            'messageError'=>'Votre message n\'a pas été',
            ));
    }

    
    public function getEntity(){
        return $this->retrieveSettingsObj()->getEntity();
    }

    public function getFormType(){
        return $this->retrieveSettingsObj()->getFormType();
    }


    public function getRedirection(){
        return $this->retrieveSettingsObj()->getRedirection();
    }

   
    public function getTemplate()
    {
        return $this->retrieveSettingsObj()->getTemplate();
    }

    public function getMailing()
    {
        $idMailing = $this->retrieveSettingsObj()->getMailing();
        if(!empty($idMailing)){

            $repoMailing = $this->container->get('doctrine')
                                ->getManager() 
                                ->getRepository('NicoFormBundle:Mailing');
            return $repoMailing->findOneById($idMailing);
        }else{
            return null;
        }
    }

    public function getMessageSuccess()
    {
        return $this->retrieveSettingsObj()->getMessageSuccess();
    }

    public function getMessageError()
    {
        return $this->retrieveSettingsObj()->getMessageError();
    }
    
}
