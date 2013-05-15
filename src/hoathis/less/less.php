<?php
    namespace  Hoathis\Less{

        class Less {
            /**
             * @var \Hoa\Compiler\Llk\Parser
             */
            private $_compiler = null;
            private $_inputDirectories = array();
            private $_inputFiles = array();
            private $_inputFilesFail = array();

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

            public function getInputFilesFail () {
                return $this->_inputFilesFail;
            }

            public function addInputDirectory ($directory, $fail = false) {
                if(!in_array($directory, $this->_inputDirectories)) {
                    $this->_inputDirectories[] = $directory;
                    $finder                    = new \Hoa\File\Finder();
                    $files                     = $finder
                        ->in($directory)
                        ->dots(false);

                    foreach ($files as $file)
                        if($file->isDir())
                            $this->addInputDirectory($file->getPathName(), $fail);
                        else if($file->getExtension() === 'less')
                            $this->addInputFile($file->getPathName(), $fail);

                }
            }


            public function addInputFile ($file, $fail = false) {
                if($fail === false) {
                    if(!in_array($file, $this->_inputFiles))
                        $this->_inputFiles[] = $file;
                }
                else {
                    if(!in_array($file, $this->_inputFilesFail))
                        $this->_inputFilesFail[] = $file;
                }
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
                    $parser = $compiler->parse("\n" . file_get_contents($file) . "\n");
                    $dump   = new \Hoa\Compiler\Visitor\Dump();
                    $visit  = $dump->visit($parser);

                    return array('output' => $visit);
                }
                catch (\Hoa\Compiler\Exception $e) {
                    return array('error' => $e->getFormattedMessage());

                }
            }

        }
    }