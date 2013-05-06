<?php
    namespace {
        require '/usr/local/lib/Hoa/Hoa/Core/Core.php';

        from('Hoa')
            ->import('File.Finder')
            ->import('File.Read')
            ->import('Compiler.Llk.~')
            ->import('Compiler.Visitor.Dump');


        class Less {
            /**
             * @var \Hoa\Compiler\Llk\Parser
             */
            private $_compiler = null;
            private $_inputDirectories = array();
            private $_inputFiles = array();

            public function setInputDirectories ($inputDirectories) {
                $this->_inputDirectories = $inputDirectories;
            }

            public function getInputDirectories () {
                return $this->_inputDirectories;
            }

            public function setInputFiles ($inputFiles) {
                $this->_inputFiles = $inputFiles;
            }

            public function getInputFiles () {
                return $this->_inputFiles;
            }

            public function addInputDirectory ($directory) {
                if(!in_array($directory, $this->_inputDirectories)) {
                    $this->_inputDirectories[] = $directory;
                    $finder                    = new \Hoa\File\Finder();
                    $files                     = $finder
                        ->in($directory)
                        ->dots(false);

                    foreach ($files as $file)
                        if($file->isDir())
                            $this->addInputDirectory($file->getPathName());
                        else if($file->getExtension() === 'less')
                            $this->addInputFile($file->getPathName());

                }
            }

            public function addInputFile ($file) {
                if(!in_array($file, $this->_inputFiles))
                    $this->_inputFiles[] = $file;
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

            public function validateFile ($file) {
                $compiler = $this->getCompiler();
                try {
                    $parser = $compiler->parse(file_get_contents($file));
                    $dump   = new \Hoa\Compiler\Visitor\Dump();
                    $visit  = $dump->visit($parser);

                    return is_string($visit);
                }
                catch (\Hoa\Compiler\Exception\UnexpectedToken $e) {
                    return $e->getFormattedMessage();

                }
            }

        }
    }