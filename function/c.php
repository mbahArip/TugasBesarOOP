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
    public function newBarang($id, $nama, $harga, $stok)
    {
        $sqlBarang = "INSERT INTO barang (id_barang, nama_barang, harga_barang)
        VALUES ('$id', '$nama', '$harga')";
        $sqlStok = "INSERT INTO stok_barang (id_barang, stok_barang)
        VALUES ('$id', '$stok')";
        $this->db->query($sqlBarang);
        $this->db->query($sqlStok);
        header('Location: adminStorage');
        exit;
    }
    public function editBarang($id, $nama, $harga, $stok)
    {
        $sql = "UPDATE barang, stok_barang
        SET nama_barang = '$nama', harga_barang = '$harga', stok_barang.stok_barang = '$stok'
        WHERE
        stok_barang.id_barang = barang.id_barang
        AND barang.id_barang = '$id'";
        $this->db->query($sql);
        header('Location: adminStorage');
        exit;
    }
    public function deleteBarang($id)
    {
        $sql = "DELETE barang, stok_barang
        FROM barang
        INNER JOIN stok_barang
        ON barang.id_barang = stok_barang.id_barang
        WHERE barang.id_barang = '$id'";
        $this->db->query($sql);
        header('Location: adminStorage');
        exit;
    }
    public function reqBarang($id, $nama, $harga, $qty, $desc)
    {
        $sql = "INSERT INTO request_barang (id_barang, nama_barang, harga_barang, qty_barang, deskripsi)
        VALUES ('$id', '$nama', '$harga', '$qty', '$desc')";
        $this->db->query($sql);
        header('Location: adminStorage');
        exit;
    }

    //Keuangan
    public function processData()
    {
        $sql = "SELECT
        transaksi.id_transaksi, tanggal_transaksi, detail_transaksi.id_karyawan, detail_transaksi.id_barang, detail_transaksi.qty_barang, detail_transaksi.total_harga
        FROM transaksi
        INNER JOIN detail_transaksi
        ON transaksi.id_transaksi = detail_transaksi.id_transaksi
        WHERE MONTH(tanggal_transaksi) = MONTH(now())";
        $result = $this->db->query($sql);

        //Check Data
        if (mysqli_num_rows($result) > 0) {
            $data = array();
            while ($x = mysqli_fetch_assoc($result)) {
                $h['id_transaksi'] = $x['id_transaksi'];
                $h['tanggal_transaksi'] = $x['tanggal_transaksi'];
                $h['id_karyawan'] = $x['id_karyawan'];
                $h['qty_barang'] = $x['qty_barang'];
                $h['total'] = $x['total_harga'];
                array_push($data, $h);
            }
        }

        $output = array();
        foreach ($data as $d) {
            $date = date("d-F-Y", strtotime($d['tanggal_transaksi']));
            $output[$date]['pemasukan'] += $d['total'];
        }
        $json = json_encode($output, JSON_PRETTY_PRINT);
        return $json;
    }
}


require('v.php');
