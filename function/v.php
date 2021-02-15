<?php

class vAdmin
{
    protected $db = null;

    public function __construct()
    {
        return $this->db = new database();
    }

    //Dashboard
    public function showNewEmployee()
    {
        $sql = "SELECT * FROM karyawan ORDER BY id_karyawan DESC LIMIT 7";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showNotes()
    {
        $sql = "SELECT * FROM notes ORDER BY id DESC LIMIT 10";
        $query = $this->db->query($sql);
        return $query;
    }

    //User
    public function showEmployee()
    {
        $sql = "SELECT * FROM karyawan";
        $query = $this->db->query($sql);
        return $query;
    }

    //Gudang
    public function showStorage()
    {
        $sql = "SELECT barang.id_barang, barang.nama_barang, barang.harga_barang, stok_barang.stok_barang
        FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showEmpty()
    {
        // $sql = "SELECT barang.id_barang, barang.nama_barang
        // FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang
        // WHERE stok_barang.stok_barang = 0";
        $sql = "SELECT * FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang
        WHERE stok_barang = 0 AND
        barang.id_barang NOT IN ( SELECT id_barang FROM request_barang )";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showRequest()
    {
        $sql = "SELECT * FROM request_barang
        WHERE status = 0 OR
        status = 1 OR
        status = 2 OR
        status = 3 OR
        status = 4
        ORDER BY id_request DESC";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showConfirm()
    {
        $sql = "SELECT * FROM request_barang
        WHERE status = 1 OR
        status = 3";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showReqKeu()
    {
        $sql = "SELECT * FROM request_barang
        WHERE status = 0
        ORDER BY id_request DESC";
        $query = $this->db->query($sql);
        return $query;
    }
    public function showDitolak()
    {
        $sql = "SELECT * FROM request_barang
        WHERE status = 2
        ORDER BY id_request DESC";
        $query = $this->db->query($sql);
        return $query;
    }

    //Keuangan
    public function showKeu($month, $year)
    {
        $sql = "SELECT * FROM lapkeuangan
        WHERE MONTH(tanggal) = $month AND
        YEAR(tanggal) = $year";
        $query = $this->db->query($sql);
        return $query;
    }
    public function sum(String $column, String $table, $month, $year)
    {
        $sql = "SELECT SUM($column) FROM $table
        WHERE MONTH(tanggal) = $month AND
        YEAR(tanggal) = $year";
        $query = $this->db->query($sql);
        $result = mysqli_fetch_assoc($query);
        return $result;
    }
    public function newTransaction()
    {
        $sql = "SELECT transaksi.id_transaksi,
        transaksi.tanggal_transaksi,
        detail_transaksi.id_karyawan,
        SUM(detail_transaksi.total_harga) AS total_harga
        FROM transaksi
        INNER JOIN detail_transaksi
        WHERE transaksi.id_transaksi = detail_transaksi.id_transaksi
        GROUP BY transaksi.id_transaksi
        ORDER BY transaksi.id_transaksi DESC
        LIMIT 7";
        $query = $this->db->query($sql);
        return $query;
    }
}

class pagination
{
    protected $db = null;

    public function __construct()
    {
        return $this->db = new database();
    }
    public function set($owo, $value)
    {
        $owo == $value;
    }

    public function paginationBarang()
    {
        global $dataPerPage;
        $dataPerPage = 15;
        global $activePage;
        $activePage = (isset($_GET['p'])) ? $_GET['p'] : 1;
        $limitStart = ($dataPerPage * $activePage) - $dataPerPage;

        $sql = "SELECT barang.id_barang, barang.nama_barang, barang.harga_barang, stok_barang.stok_barang
        FROM barang INNER JOIN stok_barang ON barang.id_barang = stok_barang.id_barang
        LIMIT $limitStart, $dataPerPage";
        $query = $this->db->query($sql);
        return $query;
    }

    public function paginationKaryawan()
    {
        global $dataPerPage;
        $dataPerPage = 15;
        global $activePage;
        $activePage = (isset($_GET['p'])) ? $_GET['p'] : 1;
        $limitStart = ($dataPerPage * $activePage) - $dataPerPage;

        $sql = "SELECT * FROM karyawan LIMIT $limitStart, $dataPerPage";
        $query = $this->db->query($sql);
        return $query;
    }
}
