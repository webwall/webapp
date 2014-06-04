<?php

namespace Webwall\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Signup extends AbstractType {

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
        // $builder->add('companyname', "text");
        $builder->add('website', "text");

        $builder->add("agreement", 'checkbox', array('label' => "I agree with the condition of use"));
    }

    public function getName() {
        return "register";
    }

}
