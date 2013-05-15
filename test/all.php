<?php
    require 'src/Less.php';


    from('Hoa')
        ->import('Console.Chrome.Text')
        ->import('Console.Readline.~')
        ->import('Console.Chrome.Style');


    $less = new \Less();
    $less->setCompiler(Hoa\Compiler\Llk::load(new Hoa\File\Read('hoa://Application/src/Less.pp')));
    $less->addInputFile('hoa://Application/test/sandbox.less');
    $less->addInputDirectory('hoa://Application/test/less/');
    try {
        $store  = array();
        $return = null;
        function test ($bool) {
            if(is_bool($bool))
                if($bool === true)
                    return \Hoa\Console\Chrome\Style::stylize('[SUCCESS]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN);
                else
                    return \Hoa\Console\Chrome\Style::stylize('[FAIL]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED);
            else
                return $bool;
        }

        $listFile = array();
        echo \Hoa\Console\Chrome\Text::columnize(array(
                                                      array(
                                                          'File',
                                                          'Result'
                                                      )
                                                 )
        );

        foreach ($less->getInputFiles() as $file) {
            echo $file . "\n";
            $listFile[] = $file;
            $out        = $less->validateFile($file);
            $bool       = array_key_exists('output', $out);
            if(array_key_exists('error', $out)) {
                $return = $out['error'];
                $bool   = false;
            }
            else {
                $return = $out['output'];
            }
            echo \Hoa\Console\Chrome\Text::columnize(array(
                                                          array(
                                                              $file,
                                                              test($bool)
                                                          )
                                                     )
            );

            if($bool === false)
                break;
        }
        if($bool === false)
            echo \Hoa\Console\Chrome\Style::stylize($return, \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED);
        else {
            echo 'Test : ' . \Hoa\Console\Chrome\Style::stylize(count($listFile) . ' files', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . ' on ' . \Hoa\Console\Chrome\Style::stylize((count($less->getInputFiles())) . ' tests', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW) . "\n";
            echo \Hoa\Console\Chrome\Style::stylize('Well done padawan', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN);
        }


    }
    catch (\Hoa\Core\Exception $e) {
        echo 'Before fail we pass : ' . \Hoa\Console\Chrome\Style::stylize((count($listFile) - 1) . ' files', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN) . ' on ' . \Hoa\Console\Chrome\Style::stylize((count($less->getInputFiles())) . ' tests', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_YELLOW) . "\n";
        echo 'Last file : ' . \Hoa\Console\Chrome\Style::stylize($file, \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED) . "\n";
        echo $e->getFormattedMessage() . "\n";
        echo $e->getTraceAsString();
    }