<?php

class Task
{

    private $db;
    private $table = 'task';

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM {$this->table}");
        return $this->db->resultSet();
    }

    public function store($name, $email, $text)
    {
        $this->db->query("INSERT INTO {$this->table} (name, email,text,status) VALUES (:name, :email, :text,0)");

        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':text', $text);

        return $this->db->execute();
    }

    public function edit($id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = {$id}");
        return $this->db->single();
    }

    public function update($id, $text, $status)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = {$id}");
        $oldText = $this->db->resultSet()[0]['text'];
        $edited = 0;
        if ($oldText != $text) {
            $edited = 1;
        }

        if ($edited == 1) {
            if (isset($status)) {
                $this->db->query("UPDATE {$this->table} SET text =:text, status =:status, edited = 1 WHERE id = {$id}");
                $this->db->bind(':text', $text);
                $this->db->bind(':status', $status);
            } else {
                $this->db->query("UPDATE {$this->table} SET edited = 1, text =:text WHERE id = {$id}");
                $this->db->bind(':text', $text);
            }
        } else {
            if (isset($status)) {
                $this->db->query("UPDATE {$this->table} SET status =:status WHERE id = {$id}");
                $this->db->bind(':status', $status);
            }
        }

        return $this->db->execute();
    }

    public function destroy($id)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = {$id}");
        return $this->db->execute();
    }

}