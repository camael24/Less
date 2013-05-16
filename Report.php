<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 16/05/13
     * Time: 09:21
     * To change this template use File | Settings | File Templates.
     */
    namespace Hoathis\Less {
        class Report {
            private $_maxChain = 0;
            private $_header = array();
            private $_headerPublish = false;
            private $_totalFile = 0;
            private $_count
                = array(
                    'suspect' => array(
                        1 => 0,
                        2 => 0
                    ),
                    'success' => array(
                        1 => 0,
                        2 => 0
                    ),
                    'fail'    => array(
                        1 => 0,
                        2 => 0
                    )
                );

            public function __construct (Provider $provider) {
                $this->loadChain($provider->getFiles());
            }

            public function setHeader () {
                $array         = func_get_args();
                $array[0]      = $this->formatCols($array[0], $this->_maxChain);
                $this->_header = $array;
            }

            public function partialContent () {

                if($this->_headerPublish === false) {
                    $this->_headerPublish = true;
                    $this->write($this->_header);
                }


                $array    = func_get_args();
                $array[0] = $this->formatCols($array[0], $this->_maxChain);
                if(array_key_exists(1, $array))
                    $array[1] = $this->resultToString($array[1], strlen($this->_header[1]), 1);
                if(array_key_exists(2, $array))
                    $array[2] = $this->resultToString($array[2], strlen($this->_header[2]), 2);

                $this->write($array);

            }

            protected function loadChain ($array) {
                $this->_totalFile = count($array);
                foreach ($array as $uri => $r)
                    $this->_chain($uri);
            }

            private function _chain ($str) {
                if(strlen($str) > $this->_maxChain)
                    $this->_maxChain = strlen($str);

            }


            protected function resultToString ($bool, $size = 0, $cols = 1) {
                if($bool === null) {
                    $this->_count['suspect'][$cols]++;

                    return \Hoa\Console\Chrome\Style::stylize($this->formatCols('[X]', $size), \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW);
                }
                switch ($bool) {
                    case true:
                        $this->_count['success'][$cols]++;

                        return \Hoa\Console\Chrome\Style::stylize($this->formatCols('[S]', $size), \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN);
                    case false:
                        $this->_count['fail'][$cols]++;

                        return \Hoa\Console\Chrome\Style::stylize($this->formatCols('[F]', $size), \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED);
                    default:
                        return '';

                }

            }

            protected function formatCols ($string, $nb) {
                if($nb < 1)
                    return $string;

                $stringLength = strlen($string);
                $rest         = $nb - $stringLength;

                return $string . str_repeat(' ', $rest);
            }

            protected function write ($array) {
                echo \Hoa\Console\Chrome\Text::columnize(array($array));
            }

            public function __toString () {
                $return  = "\n" . 'On this run you have on ' . $this->_totalFile . ' test file' . "\n";
                $grammar = '';
                $visitor = '';
                $jedi    = false;
                $success = $this->_count['success'];
                $suspect = $this->_count['suspect'];
                $fail    = $this->_count['fail'];

                if(count($success) === 2) {
                    $grammar .= '# Success :  ' . \Hoa\Console\Chrome\Style::stylize($success[1], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . ' files' . "\n";
                    $visitor .= '# Success :  ' . \Hoa\Console\Chrome\Style::stylize($success[2], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . ' files' . "\n";
                }
                if(count($suspect) === 2) {
                    $grammar .= '# Suspect :  ' . \Hoa\Console\Chrome\Style::stylize($suspect[1], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW) . ' files' . "\n";
                    $visitor .= '# Suspect :  ' . \Hoa\Console\Chrome\Style::stylize($suspect[2], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW) . ' files' . "\n";
                }
                if(count($fail) === 2) {
                    if($fail[1] === 0 && $fail[2] === 0)
                        $jedi = true;
                    $grammar .= '# Fail :  ' . \Hoa\Console\Chrome\Style::stylize($fail[1], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . ' files' . "\n";
                    $visitor .= '# Fail :  ' . \Hoa\Console\Chrome\Style::stylize($fail[2], \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . ' files' . "\n";
                }


                $return .= 'Grammar test : ' . "\n" . $grammar;
                $return .= 'Visitor test : ' . "\n" . $visitor;
                if($jedi === true)
                    $return .= \Hoa\Console\Chrome\Style::stylize(' Well done, you rox like a Jedi', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . "\n";
                else
                    $return .= \Hoa\Console\Chrome\Style::stylize('There is still a long way to go , padawan', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . "\n";


                $return .= "\n";

                return $return;
            }
        }
    }
