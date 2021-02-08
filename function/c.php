<?php
require('m.php');

class connectDatabase
{

    protected $db = null;

    public function __construct()
    {
        return $this->db = new database();
    }
}

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

class functionLogin extends connectDatabase
{
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

class searchQuery extends connectDatabase
{
    public function userSearch($keyword)
    {
        $tag = explode(':', $keyword);
        $search = trim($tag[1], ' ');
        $sql = null;

        if ($tag[0] == 'id') {
            $sql = "SELECT * FROM karyawan
                WHERE
                id_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'nama') {
            $sql = "SELECT * FROM karyawan
                WHERE
                nama_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'email') {
            $sql = "SELECT * FROM karyawan
                WHERE
                email_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'rank') {
            $sql = "SELECT * FROM karyawan
                WHERE
                rank_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'telp') {
            $sql = "SELECT * FROM karyawan
                WHERE
                telp_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'alamat') {
            $sql = "SELECT * FROM karyawan
                WHERE
                alamat_karyawan LIKE '%$search%'";
        } elseif ($tag[0] == 'gaji') {
            $sql = "SELECT * FROM karyawan
                WHERE
                gaji_karyawan LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM karyawan 
                WHERE 
                id_karyawan LIKE '%$keyword%' OR 
                nama_karyawan LIKE '%$keyword%' OR 
                email_karyawan LIKE '%$keyword%' OR 
                rank_karyawan LIKE '%$keyword%' OR 
                alamat_karyawan LIKE '%$keyword%'";
        }

        $query = $this->db->query($sql);
        return $query;
    }

    public function itemSeach($keyword)
    {
        $tag = explode(':', $keyword);
        $search = trim($tag[1], ' ');
        $sql = null;

        if ($tag[0] == 'id') {
            $sql = "SELECT * FROM barang
                WHERE
                id_barang LIKE '%$search%'";
        } elseif ($tag[0] == 'nama') {
            $sql = "SELECT * FROM barang
                WHERE
                nama_barang LIKE '%$search%'";
        } elseif ($tag[0] == 'harga') {
            $sql = "SELECT * FROM barang
                WHERE
                harga_barang LIKE '%$search%'";
        } elseif ($tag[0] == 'stok') {
            $sql = "SELECT * FROM stok_barang
                WHERE
                stok_barang LIKE '%$search%'";
        } else {
            $sql = "SELECT barang.id_barang, barang.nama_barang, barang.harga_barang, stok_barang.stok_barang
        FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang
        WHERE
        barang.id_barang LIKE '%$keyword%' OR
        barang.nama_barang LIKE '%$keyword%' OR
        barang.harga_barang LIKE '%$keyword%' OR
        stok_barang.stok_barang LIKE '%$keyword%'";
        }

        $query = $this->db->query($sql);
        return $query;
    }
}

class addQuery extends connectDatabase
{
    public function addUser($nama, $email, $posisi, $alamat, $telp)
    {
        //Fetch Last ID
        $last = "SELECT id_karyawan FROM karyawan ORDER BY id_karyawan DESC LIMIT 1";
        $query = $this->db->query($last);
        $fetchLast = mysqli_fetch_assoc($query);
        $convertToString = $fetchLast['id_karyawan'];
        $lastID = substr($convertToString, 5);
        //Get Current Year and Month
        $timeStamp = date("Ym");
        //Combine Time and Last ID
        $lastID = $timeStamp . $lastID;
        //New ID
        $newID = $lastID + 1;

        //Insert to Database
        $hashPassword = password_hash('indomaret', PASSWORD_DEFAULT);
        $sql = "INSERT INTO karyawan (id_karyawan, nama_karyawan, email_karyawan, password, rank_karyawan, alamat_karyawan, telp_karyawan) 
        VALUES('$newID', '$nama', '$email', '$hashPassword', '$posisi', '$alamat', '$telp')";
        $this->db->query($sql);
        header('Location: adminUser');
    }

    public function addItem($id, $nama, $harga, $stok)
    {
        //Fetch Last ID
        $last = "SELECT id_barang FROM barang WHERE id_barang LIKE '$id%' ORDER BY id_barang DESC LIMIT 1";
        $query = $this->db->query($last);
        $fetchLast = mysqli_fetch_assoc($query);
        $convertToString = $fetchLast['id_barang'];
        $lastID = substr($convertToString, 2);
        $IDwithoutCategory = $lastID + 1;
        //Combine with Category
        $newID = $id . '-' . $IDwithoutCategory;
        echo $newID;

        //Insert to Database
        $sqlBarang = "INSERT INTO barang (id_barang, nama_barang, harga_barang)
        VALUES ('$newID', '$nama', '$harga')";
        $sqlStok = "INSERT INTO stok_barang (id_barang, stok_barang)
        VALUES ('$newID', '$stok')";
        $this->db->query($sqlBarang);
        $this->db->query($sqlStok);
        header('Location: adminStorage');
    }

    public function addNotes($notes, $id, $user)
    {
        $sql = "INSERT INTO notes (deskripsi, id_karyawan, nama_karyawan) VALUES('$notes', '$id', '$user')";
        $this->db->query($sql);
        header('Location: adminIndex');
    }
}

class editQuery extends connectDatabase
{
    public function editUser($id, $nama, $email, $posisi, $alamat, $telp)
    {
        $sql = "UPDATE karyawan 
        SET nama_karyawan='$nama', email_karyawan='$email', rank_karyawan='$posisi', alamat_karyawan='$alamat', telp_karyawan='$telp' 
        WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
    }

    public function editItem($id, $nama, $harga, $stok)
    {
        $sql = "UPDATE barang, stok_barang
        SET nama_barang = '$nama', harga_barang = '$harga', stok_barang.stok_barang = '$stok'
        WHERE
        stok_barang.id_barang = barang.id_barang
        AND barang.id_barang = '$id'";
        $this->db->query($sql);
        header('Location: adminStorage');
    }

    public function editNotes($notes, $id)
    {
        $sql = "UPDATE notes SET deskripsi='$notes' WHERE id='$id'";
        $this->db->query($sql);
        header('Location: adminIndex');
    }
}

class deleteQuery extends connectDatabase
{
    public function deleteUser($id)
    {
        $sql = "DELETE FROM karyawan WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
    }

    public function deleteItem($id)
    {
        $sql = "DELETE barang, stok_barang
        FROM barang
        INNER JOIN stok_barang
        ON barang.id_barang = stok_barang.id_barang
        WHERE barang.id_barang = '$id'";
        $this->db->query($sql);
        header('Location: adminStorage');
    }

    public function deleteNotes($id)
    {
        $sql = "DELETE FROM notes WHERE id='$id'";
        $this->db->query($sql);
        header('Location: adminIndex');
    }
}

class extraQuery extends connectDatabase
{

    public function salaryUser($id, $gaji)
    {
        $sql = "UPDATE karyawan
        SET gaji_karyawan='$gaji'
        WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: adminUser');
    }
    public function requestItem($id, $nama, $harga, $qty, $desc)
    {
        $sql = "INSERT INTO request_barang (id_barang, nama_barang, harga_barang, qty_barang, deskripsi)
        VALUES ('$id', '$nama', '$harga', '$qty', '$desc')";
        $this->db->query($sql);
        header('Location: adminStorage');
    }
    public function getMonthlySalary()
    {
        $sql = "SELECT SUM(gaji_karyawan) FROM karyawan";
        $query = $this->db->query($sql);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }
}

class keuangan extends connectDatabase
{
    public function transactionDataToChart($month, $year)
    {
        //Get Data from Database
        $sql = "SELECT
        transaksi.id_transaksi, tanggal_transaksi, detail_transaksi.id_karyawan, detail_transaksi.id_barang, detail_transaksi.qty_barang, detail_transaksi.total_harga
        FROM transaksi
        INNER JOIN detail_transaksi
        ON transaksi.id_transaksi = detail_transaksi.id_transaksi
        WHERE MONTH(tanggal_transaksi) = $month AND
        YEAR(tanggal_transaksi) = $year";
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

        //Create Array
        $output = array();
        foreach ($data as $d) {
            $date = date("d-F-Y", strtotime($d['tanggal_transaksi']));
            $output[$date]['pemasukan'] += $d['total'];
        }
        //Encode to JSON
        $json = json_encode($output, JSON_PRETTY_PRINT);
        return $json;
    }
}

class userSettings extends connectDatabase
{
    public function uploadAvatar($id, $fileName)
    {
        $sql = "UPDATE karyawan
                    SET avatar_karyawan='$fileName'
                    WHERE id_karyawan='$id'";
        $this->db->query($sql);
        header('Location: settings');
    }
    public function getData($id)
    {
        $sql = "SELECT * FROM karyawan
        WHERE id_karyawan ='$id'";
        $query = $this->db->query($sql);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }

    public function updateData($id, $nama, $email, $alamat, $oldPass, $newPass, $newPass2, $telp)
    {
        global $error;
        if ($oldPass != '') {
            $sqlOldPass = "SELECT password FROM karyawan WHERE id_karyawan = '$id'";
            $queryOldPass = $this->db->query($sqlOldPass);
            $resultOldPass = mysqli_fetch_assoc($queryOldPass);

            if (password_verify($oldPass, $resultOldPass['password']) == 1) {
                if ($newPass == $newPass2) {
                    $passwordHash = password_hash($newPass, PASSWORD_DEFAULT);
                    $sql = "UPDATE karyawan
                    SET nama_karyawan='$nama', email_karyawan='$email', alamat_karyawan='$alamat', telp_karyawan='$telp', password='$passwordHash'
                    WHERE id_karyawan='$id'";
                    $this->db->query($sql);
                    $error = 'null';
                } else {
                    $error = 'newPass';
                }
            } else {
                $error = 'oldPass';
            }
        } else {
            $sql = "UPDATE karyawan
                    SET nama_karyawan='$nama', email_karyawan='$email', alamat_karyawan='$alamat', telp_karyawan='$telp'
                    WHERE id_karyawan='$id'";
            $this->db->query($sql);
        }
    }
}


require('v.php');
