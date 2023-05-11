<?php

namespace App\Controller;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Track {
    private SftpHandler $sftp;
    private Logger $log;
    private array $fileList = [];
    public function __construct() {
        $this->sftp = new SftpHandler();
        $this->log = new Logger('sftp');
        $this->log->pushHandler(new StreamHandler('../log/sftp.log', Level::Info));

        $this->run();
    }
    public function run() {
        while (true) {
            $this->checkForNewAndDeletedFiles();
            sleep(5);
        }
    }

    private function checkForNewAndDeletedFiles() {
        $oldFileList = $this->fileList;
        $newFileList = $this->loadFileList();
        $this->checkForNewFiles($oldFileList, $newFileList);
        $this->checkForDeletedFiles($oldFileList, $newFileList);
        $this->fileList = $newFileList;
    }

    private function checkForNewFiles($oldFileList, $newFileList) {
        $newFiles = array_diff($newFileList, $oldFileList);
        foreach ($newFiles as $newFile) {
            echo "New file: $newFile\n";
            $this->log->info("New file: $newFile");
        }
    }

    private function checkForDeletedFiles($oldFileList, $newFileList) {
        $deletedFiles = array_diff($oldFileList, $newFileList);
        foreach ($deletedFiles as $deletedFile) {
            echo "Deleted file: $deletedFile\n";
            $this->log->info("Deleted file: $deletedFile");
        }
    }

    private function loadFileList() {
        $list = $this->sftp->listDir('/');
        return $list;
    }
}