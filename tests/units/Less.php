<?php
    namespace tests\units;
    require '/var/www/Less/src/Less.php';
    use mageekguy\atoum;

    class Less extends atoum\test {

//        public function testSetter () {
//            $less = new \Less();
//            $less->setCompiler(\Hoa\Compiler\Llk::load(new \Hoa\File\Read('/var/www/Less/Less.pp')));
////            $less->addInputDirectory('/var/www/Less/lessjs/test/less');
//            $less->addInputDirectory('/var/www/Less/Less');
//
//            $this
//                ->array($less->getInputFiles())
//                ->isNotEmpty();
//            $this
//                ->array($less->getInputDirectories())
//                ->isNotEmpty();
//
//            foreach ($less->getInputFiles() as $file) {
//                $this
//                    ->string($file)
//                    ->isNotEmpty();
//                $this
//                    ->boolean(file_exists($file))
//                    ->isTrue();
//                $content = file_get_contents($file);
//                $this
//                    ->string($content)
//                    ->isNotEmpty();
//            }
//        }

        public function testValidateFile () {
            $less = new \Less();
            $less->setCompiler(\Hoa\Compiler\Llk::load(new \Hoa\File\Read('/var/www/Less/Less.pp')));
            $less->addInputDirectory('/var/www/Less/Less');
            $less->addInputDirectory('/var/www/Less/lessjs/test/less');

            $i = 0;

            foreach ($less->getInputFiles() as $file) {
                $this->dump($file);
                $v = $less->validateFile($file);
                $this
                    ->string($v)
                    ->isNotEmpty()
                    ->dump($v);

                if($i >= 0)
                    return;
                else
                    $i++;

            }

        }
    }
