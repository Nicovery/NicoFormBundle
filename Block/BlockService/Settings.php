<?php
namespace Nico\FormBundle\Block\BlockService;
/**
* Block's setting to be serialize
*/
use JMS\Serializer\Annotation\Type;
class Settings
{

	/**
	* @Type("string")
	*/
	protected $entity;
	/**
	* @Type("string")
	*/
    protected $formType;
    /**
	* @Type("string")
	*/
    protected $redirection;
    /**
	* @Type("string")
	*/
    protected $template;
    /**
	* @Type("string")
	*/
    protected $formName;
    /**
	* @Type("integer")
	*/    
    protected $mailing;
    /**
	* @Type("string")
	*/
    protected $messageError;
    /**
	* @Type("string")
	*/
    protected $messageSuccess;
	

	public function link($settings){
		foreach($settings as $setting => $value){
			if(is_string($setting)){
				$nameFunction = __NAMESPACE__.'\Settings::set'.ucfirst($setting); 
				if(method_exists($this,'set'.ucfirst($setting))){
					call_user_func($nameFunction,$value);
				}
			}
		}
	}

	public function setFormName($formName)
    {
        $this->formName = $formName;
        return $this;
    }


    public function setEntity($entity){
        $this->entity = $entity;
        return $this;
    }
    public function getEntity(){
        return $this->entity;
    }

    public function setFormType($formType){
        $this->formType = $formType;
        return $this;
    }

    public function getFormType(){
        return $this->formType;
    }

    public function setRedirection($redirection){
        $this->redirection = $redirection;
        return $this;
    }

    public function getRedirection(){
        return $this->redirection;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
        return $this;

    }

    public function getMailing()
    {
        return $this->mailing;
    }

    public function setMessageSuccess($message)
    {
        $this->messageSuccess = $message;
        return $this;
    }

    public function getMessageSuccess()
    {
        return $this->messageSuccess;
    }

    public function setMessageError($message)
    {
        $this->messageError = $message;
        return $this;
    }

    public function getMessageError()
    {
        return $this->messageError;
    }
}