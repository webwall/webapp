<?php

namespace Webwall\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Page extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', "text", array(
            "constraints" => array(new Assert\NotBlank(), new Assert\Length(array('min'=>3, 'max'=>200)))));
        $builder->add('stub', "text", array(
            "constraints" => array(new Assert\Length(array('min'=>3, 'max'=>200)))));
        $builder->add('content', 'textarea');
        $builder->add('status', 'choice', array('choices' => array('draft', 'published')));
        // $builder->add('parent', 'choice', array('draft', 'published'));
        $builder->add('pubdate', 'datetime');
        // $builder->add('companyname', "text");
        // $builder->add('website', "text");

        // $builder->add("agreement", 'checkbox', array('label' => "I agree with the condition of use"));
        $builder->add('save', 'submit');
        $builder->add('saveAndPublish', 'submit');
    }

    public function getName() {
        return "page";
    }

}
