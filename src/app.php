<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 02/05/13
     * Time: 15:47
     * To change this template use File | Settings | File Templates.
     */

    require 'Less.php';


    $less = new \Less();
    $less->setCompiler(Hoa\Compiler\Llk::load(new Hoa\File\Read('hoa://Application/Less.pp')));
//    $less->addInputDirectory('hoa://Application/lessjs/test/less');
    $less->addInputDirectory('hoa://Application/Less');


    foreach ($less->getInputFiles() as $file) {
        var_dump($file);
        $less->validateFile($file);

        return;
    }

