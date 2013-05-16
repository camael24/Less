<?php
    namespace {
        from('Hoa')
            ->import('File.Finder')
            ->import('File.Read');
    }
    namespace Hoathis\Less {

        class Provider {

            /*
             * RESULT = (TRUE | FALSE)
             */

            private $_files = array(); // Files => File = [URI] => RESULT)

            public function addFile ($uri, $result) {
                if(!array_key_exists($uri, $this->_files))
                    $this->_files[$uri] = $result;
            }

            public function addValidDirectory ($uri) {
                $this->addDirectory($uri, true);
            }

            public function addErrorDirectory ($uri) {
                $this->addDirectory($uri, false);
            }

            protected function addDirectory ($uri, $result) {
                if(!is_dir($uri))
                    exit($uri . ' is not a well formed directory');


                $finder = new \Hoa\File\Finder();
                $files  = $finder
                    ->in($uri)
                    ->dots(false);

                foreach ($files as $file)
                    if($file->isDir())
                        $this->addDirectory($file->getPathName(), $result);
                    else if($file->getExtension() === 'less')
                        $this->addFile($file->getPathName(), $result);
            }

            public function getFiles () {
                return $this->_files;
            }

        }
    }