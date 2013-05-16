<?php
    namespace {
        from('Hoa')->import('Bench.~');

        from('Hoathis')
            ->import('Less.~')
            ->import('Less.Provider')
            ->import('Less.Report');
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
                $bench->global->start();
                while (false !== $c = $this->getOption($v)) switch ($c) {

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
                    $report->setHeader('URI', 'GRAMMAR TEST' , 'VISITOR TEST' , 'TIME');

                    $bench->report->stop();

                    $bench->test->start();
                    foreach ($provider->getFiles() as $uri => $expectedResult) {
                        $tmp    = $less->test($uri);
                        $bool   = $tmp['bool'];

                        if($expectedResult === false) {
                            if($bool === true)
                                $bool = null;
                            if($bool === false)
                                $bool = true;
                        }


                        $report->partialContent($uri, $bool , true , 0);
                    }

                    echo $report; // TODO : Revoir les stats quand le visiteur sera opérationnel
                    $bench->test->stop();
                }
                if($file !== null) {
                    $bench->parse->start();

                    $parser = $less->parse($file);

                    $bench->parse->stop();
                    $bench->visit->start();

                    $dump  = new \Hoa\Compiler\Visitor\Dump();
                    $visit = $dump->visit($parser); // TODO : Change when i make the real visitor ;)
                    echo $visit;

                    $bench->visit->stop();
                }
                $bench->global->stop();

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
