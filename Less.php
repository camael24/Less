<?php
    namespace {
        from('Hoa')
            ->import('File.Finder')
            ->import('File.Read')
            ->import('Compiler.Llk.~')
            ->import('Compiler.Visitor.Dump');
    }
    namespace Hoathis\Less {

        class Less {

            /**
             * @var \Hoa\Compiler\Llk\Parser
             */
            private $_compiler = null;

            public function __construct () {
                $this->setCompiler(\Hoa\Compiler\Llk::load(new \Hoa\File\Read('hoa://Library/Less/Less.pp')));
            }

            /**
             * @param \Hoa\Compiler\Llk\Parser $compiler
             */
            public function setCompiler (\Hoa\Compiler\Llk\Parser $compiler) {
                $this->_compiler = $compiler;
            }

            /**
             * @return \Hoa\Compiler\Llk\Parser
             */
            public function getCompiler () {
                return $this->_compiler;
            }

            public function parse ($file) {
                $compiler = $this->getCompiler();

                return $compiler->parse("\n" . file_get_contents($file) . "\n");
            }

            public function test ($file) {
                try {
                    $parser = $this->parse($file);
                    $dump   = new \Hoa\Compiler\Visitor\Dump();
                    $visit  = $dump->visit($parser);

                    return array(
                        'output' => $visit,
                        'bool'   => true
                    );
                }
                catch (\Hoa\Compiler\Exception $e) {
                    return array(
                        'output' => $e->getFormattedMessage(),
                        'bool'   => false
                    );

                }
            }

        }
    }