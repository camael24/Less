<?php
    namespace {
        from('Hoa')->import('Bench.~');

        from('Hoathis')
            ->import('Less.~')
        ->import('Less.Visitor.Css')
        ->import('Less.Visitor.Exception')
        ->import('Less.Provider')
        ->import('Less.Report');
    }

    namespace Hoathis\Less\Bin {


        class Less extends \Hoa\Console\Dispatcher\Kit {


            protected $options
                = array(
                    array(
                        'debug',
                        \Hoa\Console\GetOption::NO_ARGUMENT,
                        'd'
                    ),
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
                        'nobench',
                        \Hoa\Console\GetOption::NO_ARGUMENT,
                        'b'
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

                $bench  = new \Hoa\Bench\Bench();
                $less   = new \Hoathis\Less\Less();
                $test   = false;
                $file   = null;
                $nBench = false;
                $debug  = false;
                $bench->global->start();
                while (false !== $c = $this->getOption($v)) switch ($c) {

                    case 'd':
                        $debug = true;
                        break;
                    case 't':
                        $test = true;
                        break;
                    case 'b':
                        $nBench = true;
                        break;
                    case 'f':
                        $file = $v;
                        break;
                    case 'h':
                    case '?':
                    default:
                        return $this->usage();
                        break;
                }
                if($test === true) {
                    $bench->provider->start();

                    $provider = new \Hoathis\Less\Provider();

                    $provider->addValidDirectory('hoa://Library/Less/Test/less/valid/');
                    $provider->addErrorDirectory('hoa://Library/Less/Test/less/errors/');

                    $bench->provider->stop();

                    $bench->report->start();

                    $report = new \Hoathis\Less\Report($provider);
                    $report->setHeader('URI', 'GRAMMAR TEST', 'VISITOR TEST', 'TIME');

                    $bench->report->stop();

                    $bench->test->start();
                    foreach ($provider->getFiles() as $uri => $expectedResult) {
                        if($debug === true)
                            echo $uri . "\n";
                        $hash = '_' . md5($uri);

                        $bench->$hash->start();
                        $tmp = $less->test($uri);
                        $bench->$hash->stop();

                        $bool = $tmp['bool'];
                        $time = $bench->$hash->diff();

                        if($expectedResult === false) {
                            if($bool === true)
                                $bool = null;
                            if($bool === false)
                                $bool = true;
                        }


                        $report->partialContent($uri, $bool, true, $time);
                    }

                    echo $report;
                    $bench->test->stop();
                }
                if($file !== null) {
                    $bench->parse->start();

                    $parser = $less->parse($file);

                    $bench->parse->stop();
                    $bench->visit->start();

                    $dump = new \Hoa\Compiler\Visitor\Dump();
                    echo $dump->visit($parser);

                    echo "\n" . str_repeat('-', 80) . "\n";

//
//                    $du = new \Hoathis\Less\Visitor\Css();
//                    $du->visit($parser);


//                    $du->output();

                    echo "\n" . str_repeat('-', 80) . "\n";
                    $bench->visit->stop();
                }
                $bench->global->stop();

                $bench->filter(function ($name) {
                        return (0 !== preg_match('#^_#', $name));
                    }
                );

                if($nBench === true)
                    echo $bench->drawStatistic(80);

                return;
            }


            /**
             * The command usage.
             *
             * @access  public
             * @return  int
             */
            public function usage () {

                echo 'Usage   : hoathis less:less' . "\n";
                echo 'Options :' . "\n";
                echo $this->makeUsageOptionsList(array(
                                                      'file'     => 'Parse a file and his depends',
                                                      'selftest' => 'Run suite test like lesscss.org',
                                                      'nobench'  => 'Dont display the benchmark',
                                                      'help'     => 'This help.'
                                                 )
                ), "\n";

                return;
            }
        }

    }
