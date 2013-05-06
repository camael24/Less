<?php
    require 'src/Less.php';


    from('Hoa')
        ->import('Console.Chrome.Text')
        ->import('Console.Readline.~')
        ->import('Console.Chrome.Style');


    function test ($bool) {
        if($bool === true)
            return \Hoa\Console\Chrome\Style::stylize('[SUCCESS]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_GREEN);
        else
            return \Hoa\Console\Chrome\Style::stylize('[FAIL]', \Hoa\Console\Chrome\Style::COLOR_FOREGROUND_RED);
    }

    $less = new \Less();
    $less->setCompiler(Hoa\Compiler\Llk::load(new Hoa\File\Read('hoa://Application/Less.pp')));
    $less->addInputFile('hoa://Application/test/sandbox.less');
    $less->addInputDirectory('hoa://Application/test/less/');

    $rapport = array(
        array(
            'File',
            'Result'
        )
    );

    $return = null;
    foreach ($less->getInputFiles() as $file) {
        $return = $less->validateFile($file);
        $bool   = (is_string($return)) ? false : $return;

        $rapport[] = array(
            $file,
            test($bool)
        );
        if($bool === false) {
            break;
        }


    }

    echo \Hoa\Console\Chrome\Text::columnize($rapport);
    if($return !== null)
        echo $return;
