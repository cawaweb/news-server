<?php

namespace NewsServer\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex;

class SourcesForm extends Form
{

    protected $mode = 'all';

    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null)
    {
        $this->mode = 'all';
        if ($options != null && isset($options['mode'])) {
            $this->mode = $options['mode'];
        }

        if ($this->mode == 'all') {
            $name = new Text('name', ['placeholder' => 'Name', 'required'    => true, 'class' => 'form-control']);
            $name->setLabel('Name')
                ->addValidators([
                        new PresenceOf([
                                'message' => 'This field is required'
                            ]
                        ),
                        new StringLength([
                                'max'            => 50,
                                'min'            => 1,
                                'messageMaximum' => 'The maximum value is 50',
                                'messageMinimum' => 'The minimum value is 1'
                            ]
                        )
                    ]
                );
            $this->add($name);

            $description = new TextArea('description', ['placeholder'    => 'Description', 'class' => 'form-control']);
            $description->setLabel('Description')
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
            $this->add($description);

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

            $feedUrl = new Text('feedUrl', ['placeholder' => 'Feed URL', 'class' => 'form-control']);
            $feedUrl->setLabel('Feed URL')
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
            $this->add($feedUrl);

            if ($entity != null && is_array($entity->getReplaceStrings())) {
                $replaceStrings = $entity->getReplaceStrings();
                foreach ($replaceStrings as $type => $stringsArray) {
                    $replaceStrings = new TextArea('replaceStrings_' . $type, ['value' => implode(', ', $stringsArray), 'placeholder' => 'Comma separated strings', 'class' => 'form-control']);
                    $replaceStrings->setLabel(ucfirst($type));
                    $this->add($replaceStrings);
                }
            }
        } else {
            $url = new Text('url[]', ['placeholder' => 'URL', 'class' => 'form-control', 'required' => true]);
            $url->setLabel('URL');
            $this->add($url);
        }

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
        if ($this->mode == 'all') {
            switch ($element->getName()) {
                case 'Save':
                    // This is a button
                    echo $element . PHP_EOL;
                    echo $this->tag->linkTo(['/web/sources', 'Cancel', 'class' => 'btn btn-inverse']) . PHP_EOL;
                    break;

                case 'images':
                    echo '<label>Replace Strings</label>' . PHP_EOL;

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
        } else {
            if ($element->getName() != 'Save') {
                // This is decorator for standard elements
                $messages = $this->getMessagesFor($element->getName());
                $errors   = '';

                echo '<div id="urls" class="form-group">' . PHP_EOL;

                if (count($messages)) {
                    echo '<div class="alert alert-danger">' . PHP_EOL;
                    foreach ($messages as $message) {
                        $errors .= $message;
                        break;
                    }
                } else {
                    echo '<div class="input-group">' . PHP_EOL;
                }

                echo $element . PHP_EOL;
                if ($this->mode == 'add') {
                    echo '<span class="input-group-btn">
                            <button class="btn btn-info" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                          </span>' . PHP_EOL;
                }
                echo $errors;
                echo '</div>' . PHP_EOL;
                echo '</div>' . PHP_EOL;
            } else {
                // This is a button
                echo '<br>';
                echo $element . PHP_EOL;
            }
        }
    }
}
