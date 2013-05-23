<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 16/05/13
     * Time: 17:05
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Less\Visitor {

        class Css implements \Hoa\Visitor\Visit {
            private $_filename = null;
            private $_variable = array();
            private $_ruleset = array();
            private $_keyword = array();

            public function output () {
                print_r($this->_variable);
                print_r($this->_ruleset);
            }


            public function visit (\Hoa\Visitor\Element $element, &$handle = null, $eldnah = null) {
                $this->dispatch($element);

                return null;
            }


            protected function setFilename ($filename) {
                $this->_filename = $filename;
            }

            protected function addVariable ($name, $value) {
                $this->_variable[$name] = $value;
            }

            protected function addKeyword ($name, $value) {
                $this->_keyword[$name] = $value;
            }

            protected function addRulset ($name, $rules) {
                $this->_ruleset[$name] = $rules;
            }

            protected function addRule ($rulset, $property, $value) {
                $this->_ruleset[$rulset][$property] = $value;
            }

            protected function isDefineRuleset ($name) {
                return array_key_exists($name, $this->_ruleset);
            }

            protected function isDefineVariable ($name) {
                var_dump($this->_variable);

                return array_key_exists($name, $this->_variable);
            }

            protected function getVariable ($name) {
                $old  = $name;
                $name = preg_replace('#[^[:alnum:]\-]#', '', (string)$name);
                if($this->isDefineVariable($name))
                    return $this->_variable[$name];
                else
                    throw new \Hoathis\Less\Visitor\Exception('Variable %s are not define before his origin name is %s', 0, array(
                                                                                                                                 $name,
                                                                                                                                 $old
                                                                                                                            ));

            }


            protected function isRule ($string) {
                if(is_object($string))
                    $string = $string->getId();

                return (strpos($string, '#') === 0);
            }

            protected function dispatchChild ($element) {
                foreach ($element->getChildren() as $child)
                    $this->dispatch($child);
            }

            protected function _getValue ($token) {
                if($token === null or !$token->isToken())
                    return $this->dispatch($token);
                else
                    switch ($token->getValueToken()) {
                        case 'comma':
                            return $token->getValueValue() . ' ';
                            break;
                        default:
                            return trim($token->getValueValue());
                    }
            }


            protected function _helpGetPairValue ($element, &$a, &$b) {
                $child = $element->getChildren();
                $a     = $this->_getValue(array_shift($child));
                $b     = $this->_getValue(array_shift($child));
            }

            protected function _helpGetSingleValue ($element, &$a) {
                $child = $element->getChildren();
                $a     = $this->_getValue(array_shift($child));
            }

            protected function _helpGetSingleConcatValue ($element, &$a) {
                $child = $element->getChildren();
                foreach ($child as $c)
                    $a .= $this->_getValue($c);
            }

            protected function _helpGetPairConcatValue ($element, &$a, &$b) {
                $child = $element->getChildren();
                $a     = $this->_getValue(array_shift($child));
                foreach ($child as $c)
                    $b .= $this->_getValue($c);

            }


            protected function dispatch ($element) {

                $id = $element->getId();
                switch ($id) {
                    case '#set':
                        $name  = null;
                        $value = null;
                        $this->_helpGetPairValue($element, $name, $value);
                        $this->addVariable($name, $value);
                        break;
                    case '#ruleset':
                        $this->_ruleset($element);
                        break;
                    case '#root':
                        $this->dispatchChild($element);
                        break;
                    case '#variable';
                        $variable = null;
                        $this->_helpGetSingleValue($element, $variable);

                        return $this->getVariable($variable);
                        break;
                    case '#variableRelative':
                        $variableRelative = null;
                        $variable         = null;
                        $this->_helpGetSingleValue($element, $variableRelative);
                        $variable = $this->getVariable($variableRelative);

                        return $this->getVariable($variable);
                        break;
                    case '#parens':
                        $a = null;
                        $this->_helpGetSingleConcatValue($element, $a);

                        return $a;
                        break;
                    case '#rule': // Its an private rule ;) not accessible with an _help*
                        break;
                    default:
                        echo $id . ' is not reconize yet' . "\n";

                }

                return null;
            }

            protected function _ruleset ($element) {
                $selector = null;
                $this->_helpGetSingleConcatValue($element, $selector);

                foreach ($element->getChildren() as $e)
                    switch ($e->getId()) {
                        case '#rule':
                            $property = null;
                            $value    = null;
                            $this->_helpGetPairConcatValue($e, $property, $value);
                            $this->addRule($selector, $property, $value);
                            break;
                        default:

                    }
            }


        }
    }
