<?php

namespace Nico\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailing
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Nico\FormBundle\Entity\MailingRepository")
 */
class Mailing
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var string
    * @ORM\Column(name="nom", type="string", length=255)
    */
    private $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="destinataires", type="text")
     */
    private $destinataires;

    /**
     * @var string
     *
     * @ORM\Column(name="emetteur", type="string", length=255)
     */
    private $emetteur;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string", length=255)
     */
    private $contentType;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template;


    /**
     * Get id
     *
     * @return integer 
     */

    public function __construct(){
        $this->contentType = 'text/html';
        $this->template = 'NicoFormBundle:Mailing:contact.txt.twig';
    }
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set destinataires
     *
     * @param string $destinataires
     * @return Email
     */
    public function setDestinataires($destinataires)
    {
        $this->destinataires = $destinataires;
    
        return $this;
    }

    /**
     * Get destinataires
     *
     * @return string 
     */
    public function getDestinataires()
    {
        return $this->destinataires;
    }

    /**
     * Set emetteur
     *
     * @param string $emetteur
     * @return Email
     */
    public function setEmetteur($emetteur)
    {
        $this->emetteur = $emetteur;
    
        return $this;
    }

    /**
     * Get emetteur
     *
     * @return string 
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     * @return Email
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    
        return $this;
    }

    /**
     * Get sujet
     *
     * @return string 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     * @return Email
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    
        return $this;
    }

    /**
     * Get contentType
     *
     * @return string 
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Email
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    
        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Mailing
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
}