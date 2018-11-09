<?php
namespace controllers {
    /*
    Person Class
     */
    class Person
    {
        //Singleton PDO connect to database
        private $PDO;

        /*
        __construct
        Conectando ao banco de dados
         */
        public function __construct()
        {
            $this->PDO = new \PDO('mysql:host=127.0.0.1;dbname=api', 'api', '123456'); //Conexão
            $this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->PDO->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);
        }
        /*
        lista
        Listand pessoas
         */
        function list() {
            global $app;
            $sth = $this->PDO->prepare("SELECT * FROM person");
            $sth->execute();
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $app->render('default.php', ["data" => $result], 200);
        }
        /*
        get
        param $id
        Pega pessoa pelo id
         */
        public function get($id)
        {
            global $app;
            $sth = $this->PDO->prepare("SELECT * FROM person WHERE id = :id");
            $sth->bindValue(':id', $id);
            $sth->execute();
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            $app->render('default.php', ["data" => $result], 200);
        }
        /*
        nova
        Cadastra pessoa
         */
        public function create()
        {
            global $app;
            // Check if contain all data required to create a new person
            $data = json_decode($app->request->getBody(), true);
            $data = (sizeof($data) == 0) ? $_POST : $data;
            $keys = array_keys($data); //Paga as chaves do array
            $sth = $this->PDO->prepare("INSERT INTO person (" . implode(',', $keys) . ") VALUES (:" . implode(",:", $keys) . ")");
            foreach ($data as $key => $value) {
                $sth->bindValue(':' . $key, $value);
            }
            $sth->execute();
            //Retorna o id inserido
            $app->render('default.php', ["data" => ['id' => $this->PDO->lastInsertId()]], 200);
        }

        /*
        editar
        param $id
        Editando pessoa
         */
        public function edit($id)
        {
            global $app;
            $data = json_decode($app->request->getBody(), true);
            $data = (sizeof($data) == 0) ? $_POST : $data;
            $sets = [];
            foreach ($data as $key => $VALUES) {
                $sets[] = $key . " = :" . $key;
            }

            $sth = $this->PDO->prepare("UPDATE person SET " . implode(',', $sets) . " WHERE id = :id");
            $sth->bindValue(':id', $id);
            foreach ($data as $key => $value) {
                $sth->bindValue(':' . $key, $value);
            }
            try {
                // Return edition
                $status = $sth->execute();
                $app->render('default.php', ["data" => ['status' => $status]], 200);
            } catch (Exception $e) {
                $app->render('default.php', "Error requesting API check your request", 500);
            }
        }

        /**
         * Remove person from database
         */
        public function delete($id)
        {
            global $app;
            $sth = $this->PDO->prepare("DELETE FROM person WHERE id = :id");
            $sth->bindValue(':id', $id);
            $app->render('default.php', ["data" => ['status' => $sth->execute() == 1]], 200);
        }
    }
}
