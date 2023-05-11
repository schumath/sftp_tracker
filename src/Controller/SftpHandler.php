<?php

namespace App\Controller;

use phpseclib3\Net\SFTP;

class SftpHandler {
    private SFTP $sftp;
    private string $server = '127.0.0.1';
    private int $port = 2222;
    private string $username = 'tester';
    private string $password = 'password';

    /**
     * @throws \Exception
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * @throws \Exception
     */
    public function connect(): void
    {
        $this->sftp = new SFTP($this->server, $this->port);
        $isLoggedIn = $this->sftp->login($this->username, $this->password);
        if (!$isLoggedIn) {
            throw new \Exception('Login failed');
        }
    }

    /**
     * @throws \Exception
     */
    public function listDir($dir) : array {
        $list = $this->sftp->nlist($dir);
        if (!$list) {
            throw new \Exception('Could not list directory');
        }
        return $list;
    }
}