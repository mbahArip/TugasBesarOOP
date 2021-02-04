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

    //Keuangan
    public function showKeu()
    {
        $sql = "SELECT * FROM lapkeuangan
        WHERE MONTH(tanggal) = MONTH(now())";
        $query = $this->db->query($sql);
        return $query;
    }
    public function sum(String $column, String $table)
    {
        $sql = "SELECT SUM($column) FROM $table
        WHERE MONTH(tanggal) = MONTH(now())";
        $query = $this->db->query($sql);
        return $query;
    }
}
