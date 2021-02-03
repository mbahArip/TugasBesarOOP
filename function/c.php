<?php
require('m.php');


class sessionCookie
{
    public function createSession($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public function createCookies($name, $value, $day)
    {
        return setcookie($name, $value, time() + (86400 * 1));
    }

    public function _checkSession(String $name)
    {
        if ($_SESSION[$name] === NULL) {
            header('Location: logout');
            exit;
        }
    }
    public function _checkRank($condition)
    {
        if ($condition) {
            header('Location: logout');
            exit;
        }
    }
}

class functionLogin
{
    protected $db = null;

    public function __construct()
    {
        return $this->db = new database();
    }
    public function logIn($id, $password)
    {
        $sql = "SELECT * FROM karyawan WHERE id_karyawan = '$id'";
        $queryResult = $this->db->query($sql);

        if (mysqli_num_rows($queryResult) == 1) {
            $getUser = mysqli_fetch_assoc($queryResult);

            if (password_verify($password, $getUser['password']) == true) {
                $sc = new sessionCookie();

                if ($getUser['rank_karyawan'] == 'debug') {

                    $sc->createSession('login', true);
                    $sc->createCookies('session', password_hash($getUser['rank_karyawan'], PASSWORD_DEFAULT), 365);
                    $sc->createCookies('nama', $getUser['nama_karyawan'], 365);
                    $sc->createCookies('id', $getUser['id_karyawan'], 365);

                    $sc->createCookies('debug', true, 365);

                    header('Location: index');
                    exit;
                }

                $sc->createSession('login', true);
                $sc->createCookies('session', password_hash($getUser['rank_karyawan'], PASSWORD_DEFAULT), 365);
                $sc->createCookies('nama', $getUser['nama_karyawan'], 365);
                $sc->createCookies('id', $getUser['id_karyawan'], 365);


                header('Location: index');
                exit;
            }
        }
    }
}

class cAdmin
{
    protected $db = null;

    public function __construct()
    {
        return $this->db = new database();
    }

    //Notes
    public function addNotes($notes, $id, $user)
    {
        $sql = "INSERT INTO notes (deskripsi, id_karyawan, nama_karyawan) VALUES('$notes', '$id', '$user')";
        $this->db->query($sql);
        header('Location: adminIndex');
        exit;
    }
    public function editNotes($notes, $id)
    {
        $sql = "UPDATE notes SET deskripsi='$notes' WHERE id='$id'";
        $this->db->query($sql);
        header('Location: adminIndex');
        exit;
    }
    public function deleteNotes($id)
    {
        $sql = "DELETE FROM notes WHERE id='$id'";
        $this->db->query($sql);
        header('Location: adminIndex');
        exit;
    }

    //Employee
    public function searchQuery($keyword)
    {
        $sql = "SELECT * FROM karyawan 
        WHERE 
        id_karyawan LIKE '%$keyword%' OR 
        nama_karyawan LIKE '%$keyword%' OR 
        email_karyawan LIKE '%$keyword%' OR 
        rank_karyawan LIKE '%$keyword%' OR 
        alamat_karyawan LIKE '%$keyword%'";
        $query = $this->db->query($sql);
        return $query;
    }
    public function newEmployee($nama, $email, $posisi, $alamat, $telp)
    {
        $hashPassword = password_hash('indomaret', PASSWORD_DEFAULT);
        $sql = "INSERT INTO karyawan (nama_karyawan, email_karyawan, password, rank_karyawan, alamat_karyawan, telp_karyawan) 
        VALUES('$nama', '$email', '$hashPassword', '$posisi', '$alamat', '$telp')";
        $this->db->query($sql);
        header('Location: adminUser');
        exit;
    }
    public function editEmployee($id, $nama, $email, $posisi, $alamat, $telp)
    {
        $sql = "UPDATE karyawan 
        SET nama_karyawan='$nama', email_karyawan='$email', rank_karyawan='$posisi', alamat_karyawan='$alamat', telp_karyawan='$telp' 
        WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
        exit;
    }
    public function deleteEmployee($id)
    {
        $sql = "DELETE FROM karyawan WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
        exit;
    }
    public function salaryEmployee($id, $gaji)
    {
        $sql = "UPDATE karyawan
        SET gaji_karyawan='$gaji'
        WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
        exit;
    }

    //Gudang

    public function searchGudang($keyword)
    {
        $sql = "SELECT barang.id_barang, barang.nama_barang, barang.harga_barang, stok_barang.stok_barang
        FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang
        WHERE
        barang.id_barang LIKE '%$keyword%' OR
        barang.nama_barang LIKE '%$keyword%'";
        $query = $this->db->query($sql);
        return $query;
    }
}


require('v.php');
