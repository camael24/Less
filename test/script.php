<?php
    require 'src/Less.php';


    from('Hoa')
        ->import('Console.Chrome.Text')
        ->import('Console.Readline.~')
        ->import('Console.Chrome.Style');


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

    $add = function ($file, $bool) use (&$rapport) {
        $rapport[] = array(
            $file,
            test($bool)
        );
    };

    $less = new \Less();
    $less->setCompiler(Hoa\Compiler\Llk::load(new Hoa\File\Read('hoa://Application/Less.pp')));
    $less->addInputFile('hoa://Application/test/sandbox.less');
    $less->addInputDirectory('hoa://Application/test/less/');

    $add('File', 'Result');

    foreach ($less->getInputFiles() as $file) {
        $return = $less->validateFile($file);
        $bool   = (is_string($return)) ? false : $return;

        $add($file, $bool);
        if($bool === false)
            break;
    }

    echo \Hoa\Console\Chrome\Text::columnize($rapport);
    if($return !== null)
        echo $return;
