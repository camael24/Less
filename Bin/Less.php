<?php
    namespace {
    }

    namespace Hoathis\Less\Bin {


        class Less extends \Hoa\Console\Dispatcher\Kit {


            protected $options = array();


            /**
             * The entry method.
             *
             * @access  public
             * @return  int
             */
            public function main () {

                echo 'Foo';

                return;
            }


            /**
             * The command usage.
             *
             * @access  public
             * @return  int
             */
            public function usage () {

                echo 'Usage   : hoathis context:set name', "\n", 'Options :', "\n", $this->makeUsageOptionsList(array(
                                                                                                                     'copy'  => 'create new context from current context.',
                                                                                                                     'file'  => 'add an external file to context configuration',
                                                                                                                     'force' => 'Force parameter',
                                                                                                                     'help'  => 'This help.'
                                                                                                                )
                ), "\n";

                return;
            }
        }

    }
