<?php
    namespace {
    }

    namespace Hoathis\Less\Bin {


        class Less extends \Hoa\Console\Dispatcher\Kit {


            protected $options
                = array(
                    array(
                        'file',
                        \Hoa\Console\GetOption::REQUIRED_ARGUMENT,
                        'f'
                    ),
                    array(
                        'selftest',
                        \Hoa\Console\GetOption::NO_ARGUMENT,
                        't'
                    ),
                    array(
                        'help',
                        \Hoa\Console\GetOption::NO_ARGUMENT,
                        'h'
                    ),
                    array(
                        'help',
                        \Hoa\Console\GetOption::NO_ARGUMENT,
                        '?'
                    )
                );


            /**
             * The entry method.
             *
             * @access  public
             * @return  int
             */
            public function main () {

                while (false !== $c = $this->getOption($v)) switch ($c) {

                    case 'h':
                    case '?':
                    default:
                        return $this->usage();
                        break;
                }


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

                echo 'Usage   : hoathis less:less', "\n", 'Options :', "\n", $this->makeUsageOptionsList(array(
                                                                                                              'file'     => 'Parse a file and his depends',
                                                                                                              'selftest' => 'Run suite test like lesscss.org',
                                                                                                              'help'     => 'This help.'
                                                                                                         )
                ), "\n";

                return;
            }
        }

    }
