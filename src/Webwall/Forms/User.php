<?php

namespace Webwall\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email', "email", array(
            "constraints" => array(new Assert\NotBlank(), new Assert\Email()),
            "attr" => array("placeholder" => "email")));
        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'options' => array('required' => true),
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
        $builder->add('firstname', "text", array(
            "constraints" => array(new Assert\NotBlank(), new Assert\Length(array('min'=>3, 'max'=>200)))));
        $builder->add('surname', "text", array(
            "constraints" => array(new Assert\NotBlank(), new Assert\Length(array('min'=>3, 'max'=>200)))));
        $builder->add('active', 'checkbox', array(
              'required' => false
            ));
        $builder->add('permissions', 'choice', array(
              "choices" => array(0 => 'regular', 5 => 'admin')
            ));
        // $builder->add('companyname', "text");
        // $builder->add('website', "text");
    }

    public function getName() {
        return "user";
    }

}
