<?php

namespace NewsServer\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex;

class ItemsForm extends Form
{

    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null)
    {
        $title = new Text('title', ['placeholder' => 'Title', 'required' => true, 'class' => 'form-control']);
        $title->setLabel('Title')
            ->addValidators([
                    new PresenceOf([
                            'message' => 'This field is required'
                        ]
                    ),
                    new StringLength([
                            'max'            => 100,
                            'min'            => 1,
                            'messageMaximum' => 'The maximum value is 100',
                            'messageMinimum' => 'The minimum value is 1'
                        ]
                    )
                ]
            );
        $this->add($title);

        $intro = new TextArea('intro', ['placeholder'  => 'Introduction', 'required' => true, 'class' => 'form-control']);
        $intro->setLabel('Introduction')
            ->addValidators([
                    new StringLength([
                            'max'            => 200,
                            'min'            => 1,
                            'messageMaximum' => 'The maximum value is 200',
                            'messageMinimum' => 'The minimum value is 1'
                        ]
                    )
                ]
            );
        $this->add($intro);

        $content = new TextArea('content', ['placeholder'  => 'Content', 'required' => true, 'class' => 'form-control wysiwyg']);
        $content->setLabel('Content')
            ->addValidators([
                    new StringLength([
                            'max'            => 100000,
                            'min'            => 1,
                            'messageMaximum' => 'The maximum value is 100000',
                            'messageMinimum' => 'The minimum value is 1'
                        ]
                    )
                ]
            );
        $this->add($content);

        $url = new Text('url', ['placeholder' => 'URL', 'class' => 'form-control']);
        $url->setLabel('URL')
            ->addValidators([
                new StringLength([
                    'max'            => 500,
                    'min'            => 0,
                    'messageMaximum' => 'The maximum length is 500',
                    'messageMinimum' => 'The minimum length is 0'
                    ]
                ),
                new Regex([
                    'pattern' => '/^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)((\&|\?)(.*?)\=(.*?))?$/',
                    'message' => 'This value must be an URL'
                    ])
            ]);
        $this->add($url);

        $submit = new Submit('Save', [
                'class' => 'btn btn-success'
            ]
        );
        $this->add($submit);
    }

    /**
     * @param $name
     */
    public function renderDecorated($name)
    {
        $element = $this->get($name);
        switch ($element->getName()) {
            case 'Save':
                // This is a button
                echo $element . PHP_EOL;
                echo $this->tag->linkTo(['/web/items', 'Cancel', 'class' => 'btn btn-inverse']) . PHP_EOL;
                break;

            default:
                // This is decorator for standard elements
                $messages = $this->getMessagesFor($element->getName());
                $errors   = '';

                if (count($messages)) {
                    echo '<div class="alert alert-danger">' . PHP_EOL;
                    foreach ($messages as $message) {
                        $errors .= $message;
                        break;
                    }
                } else {
                    echo '<div class="form-group">' . PHP_EOL;
                }

                echo '<label for="' . $element->getName() . '">' . $element->getLabel() . '</label>' . PHP_EOL;
                echo $element . PHP_EOL;
                echo $errors;
                echo '</div>' . PHP_EOL;
                break;
        }
    }
}
