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
                    'suspect' => 0,
                    'success' => 0,
                    'fail'    => 0
                );

            public function __construct (Provider $provider) {
                $this->loadChain($provider->getFiles());
            }

            public function setHeader () {
                $array         = func_get_args();
                $array[0]      = $this->formatFirsCols($array[0]);
                $this->_header = $array;
            }

            public function partialContent () {

                if($this->_headerPublish === false) {
                    $this->_headerPublish = true;
                    $this->write($this->_header);
                }


                $array    = func_get_args();
                $array[0] = $this->formatFirsCols($array[0]);
                $array[1] = $this->resultToString($array[1]);

                $this->write($array);

            }

            protected function loadChain ($array) {
                $this->_totalFile = count($array);
                foreach ($array as $uri => $r)
                    $this->_chain($uri);
            }

            private function _chain ($str) {
                if(strlen($str) > $this->_maxChain)
                    if(($str % 2) === 0)
                        $this->_maxChain = strlen($str);
                    else
                        $this->_maxChain = strlen($str) + 1;
            }


            protected function resultToString ($bool) {
                if($bool === null) {
                    $this->_count['suspect']++;

                    return \Hoa\Console\Chrome\Style::stylize('[X]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW);
                }
                switch ($bool) {
                    case true:
                        $this->_count['success']++;

                        return \Hoa\Console\Chrome\Style::stylize('[S]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN);
                    case false:
                        $this->_count['fail']++;

                        return \Hoa\Console\Chrome\Style::stylize('[F]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED);
                    default:

                }

            }

            protected function formatFirsCols ($string) {
                $stringLength = strlen($string);
                $rest         = $this->_maxChain - $stringLength;

                return $string . str_repeat(' ', $rest);
            }

            protected function write ($array) {
                echo \Hoa\Console\Chrome\Text::columnize(array($array));
            }

            public function __toString () {
                $total    = $this->_totalFile;
                $tSuccess = $this->_count['success'];
                $tSuspect = $this->_count['suspect'];
                $tFail    = $this->_count['fail'];

                $return = "\n" . 'On this run you have on ' . $total . ' test file' . "\n";
                $return .= '# Success :  ' . \Hoa\Console\Chrome\Style::stylize($tSuccess, \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . ' files' . "\n";
                $return .= '# Suspect :  ' . \Hoa\Console\Chrome\Style::stylize($tSuspect, \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW) . ' files' . "\n";
                $return .= '# Fail    :  ' . \Hoa\Console\Chrome\Style::stylize($tFail, \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . ' files' . "\n";

                $return .= "\n";


                if($tFail === 0)
                    $return .= \Hoa\Console\Chrome\Style::stylize(' Well done, you rox like a Jedi', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . "\n";
                else
                    $return .= \Hoa\Console\Chrome\Style::stylize('There is still a long way to go , padawan', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . "\n";

                $return .= "\n";

                return $return;
            }
        }
    }
