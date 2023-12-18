<?php

class ML_FiletoolsExperimental_Helper_Filetools {

    protected $sPath = '';
    protected $sContent = '';

    public function setPath($sPath) {
        $this->sPath = $sPath;
        return $this;
    }

    public function setContent($sContent) {
        $this->sContent = $sContent;
        return $this;
    }

    public function setModifiedTime($iTime) {
        if ($this->exist()) {
            touch($this->sPath, $iTime);
        }
        return $this;
    }

    public function getCreateTime() {
        if ($this->exist()) {
            return filectime($this->sPath);
        }else{
            return null;
        }
    }

    public function getModifiedTime() {
        if ($this->exist()) {
            return filemtime($this->sPath);
        }else{
            return null;
        }
    }

    public function save() {
        if ($this->sPath != '') {
            file_put_contents($this->sPath, $this->sContent);
            return $this;
        } else {
            throw new ML_Filesystem_Exception("file name is not set");
        }
    }

    public function getContent() {
        if ($this->exist()) {
            $sContent = file_get_contents($this->sPath);           
        }else{
            throw new ML_Filesystem_Exception('file dont Exists');
        }
        $this->sContent = $sContent; 
        return $sContent;
    }

    public function delete() {
        if (file_exists($this->sPath) && !is_dir($this->sPath)) {
            unlink($this->sPath);
        }
        return $this;
    }

    protected function exist() {
        if ($this->sPath != '') {
            if (file_exists($this->sPath)) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new ML_Filesystem_Exception("file name is not set");
        }
    }

}
