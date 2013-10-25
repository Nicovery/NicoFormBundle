THIS IS A BETA VERSION

How to use with the NicoMailchimpBundle
---------------------------------------
To a form to mailchimp you have to add the right fields in the FormType ::

	 public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', 'text',array('label'=>'Nom*'))
            ->add('firstname', 'text',array('label'=>'Prénom*'))
            ->add('newsletterMailchimp','checkbox',array(
                'required'=>false,
                'label'=>'Inscription Newsletter Mailchimp',
                ))
            ->add('listeId','hidden',array(
                'data' => 'a9e8a9ab70',
                ))
            ->add('mailchimpFieldsBind','hidden',array(
                    'mapped'=>false,
                    'data'=>'Nico\ContactBundle\FieldsBind\ContactMailchimpFieldsBind',
                ))
        ;

    }

Fields description
-------------------
The fields must have the same name:
* newsletterMailchimp: the checkbox to subscribe or not to the mailchimp list
* listeId: the list's ID 
* mailchimpFieldsBind: the class that bind the form data and the mailchimp vars

MailchimpFieldsBind
-------------------
You can create several mailchimpFieldsBind::

use Nico\MailchimpBundle\FieldsBind\BaseFieldsFind;
class ContactMailchimpFieldsBind extends BaseFieldsFind
{
	
	public function getFieldsBind($data){
		return array(
			 'FNAME'=>$data['prenom'],
			 'LNAME'=>$data['nom'],
			);
	}
}

Note:
~~~~~
According to the data receveid you link to the mailchimp variables