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

        $add = function ($file, $bool, $time = '') use (&$rapport) {
            if($time < 0)
                $time = '';
            $rapport[] = array(
                $file,
                test($bool),
                $time
            );
        };



        $add('File', 'Result', 'Time');

        foreach ($less->getInputFiles() as $file) {
            $start = microtime();
            $out   = $less->validateFile($file);
            $bool  = array_key_exists('output', $out);
            $end   = microtime();

            if(array_key_exists('error', $out)) {
                $return = $out['error'];
                $bool   = false;
            }
            else {
                $return = $out['output'];
            }
            $add($file, $bool, round(($end - $start), 5));
            if($bool === false)
                break;
        }

        echo \Hoa\Console\Chrome\Text::columnize($rapport) . "\n";
    }
    catch (\Hoa\Core\Exception $e) {
        echo $e->getFormattedMessage() . "\n";
        echo $e->getTraceAsString();
    }