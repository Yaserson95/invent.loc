<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
//use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType{
	public const TITLE = 'Регистрация';
	
    public function buildForm(FormBuilderInterface $builder, array $options): void{
		
		
        $builder
            ->add('login', TextType::class, ['label' => 'Логин'])
			->add('email', EmailType::class, ['label' => 'Адрес эл. почты'])
			->add('firstname', TextType::class, ['label' => 'Фамилия'])
			->add('lastname', TextType::class, ['label' => 'Имя'])
			->add('middlename', TextType::class, ['label' => 'Отчество', 'required' => false])
            ->add('plainPassword', PasswordType::class, $this->passwdField('Пароль'))
			->add('rePassword', PasswordType::class, $this->passwdField('Повторите пароль'))
			->add('register', SubmitType::class,['label' => 'Зарегистрироваться'])
			->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
	
	private function passwdField(string $label):array{
		return [
			// instead of being set onto the object directly,
			// this is read and encoded in the controller
			'mapped' => false,
			'attr' => ['autocomplete' => 'new-password'],
			'constraints' => [
				new NotBlank([
					'message' => 'Please enter a password',
				]),
				new Length([
					'min' => 6,
					'minMessage' => 'Your password should be at least {{ limit }} characters',
					// max length allowed by Symfony for security reasons
					'max' => 4096,
				]),
			],
			'label' => $label
		];
	}
	
	public function onPreSubmit(FormEvent $event){
		$data = $event->getData();
		$form = $event->getForm();
		if($data['plainPassword'] != $data['rePassword']){
			$form->addError(new FormError("Пароль не совпадает с поддверждением!"));
		}
		$event->setData($data);
	}
}
