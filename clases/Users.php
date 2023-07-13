<?php


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Users
{

    private $conn;


    public function __construct()
    {
        $db = new Database();

        $this->conn = $db->dbConnection();
    }

    public function selectAll()
    {

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== 'GET') :

            echo json_encode([
                "status" => 404,
                "message" => "Method Not Alowed"
            ]);

            exit;

        else :

            $sql = "select * from usuarios";

            $query = $this->conn->prepare($sql);

            $query->execute();

            $data = $query->fetchAll();

            $array = [];

            foreach ($data as $row) {
                $array[] = [
                    "id" => $row['id'],
                    "name" => $row['nombre'],
                    "email" => $row['correo'],
                    "phone" => $row['telefono'],
                    "status" => $row['status'],
                    "rol" => $row['rol_id'],
                ];
            }



            echo json_encode([
                "total rows" => $query->rowCount(),
                "rows" => $array,
            ]);

        endif;
    }


    //***************************************************** */

    public function selectOne($id)
    {


        $sql = "select * from usuarios where id = :id";

        $query = $this->conn->prepare($sql);

        $query->bindValue(":id", $id, PDO::PARAM_INT);

        $query->execute();

        $data = $query->fetch();


        $array = [
            "id" => $data['id'],
            "name" => $data['nombre'],
            "email" => $data['correo'],
            "phone" => $data['telefono'],
            "status" => $data['status'],
            "rol" => $data['rol_id'],
        ];




        json_encode($array);
    }

    //*************************************** */
    public function insert()
    {


        $request_data = json_decode(file_get_contents("php://input"), true);

        $name = $request_data['name'];
        $phone = $request_data['phone'];
        $password = $request_data['password'];
        $email = $request_data['email'];



        $sql = "insert into spending_tracker.usuarios (correo,password,telefono,nombre) values (:email,:password,:phone,:name)";

        $query = $this->conn->prepare($sql);

        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->bindValue(":phone", $phone, PDO::PARAM_INT);
        $query->bindValue(":password", $password, PDO::PARAM_STR);
        $query->bindValue(":email", $email, PDO::PARAM_STR);



        $array = [
            "error" => "error al insertar",
            "status" => "error"
        ];

        if ($query->execute()) {
            $array = [
                $data = [
                    "id" => $this->conn->lastInsertId(),
                    "name" => $name,
                    "phone" => $phone,
                    "password" => $password,
                    "email" => $email,
                ],

                "status" => "success"

            ];
        }

        json_encode($array);
    }


    //*********************************************** */

    public function update()
    {

        $request_data = json_decode(file_get_contents("php://input"), true);

        $id = $request_data['id'];
        $name = $request_data['name'];
        $phone = $request_data['phone'];
        $password = $request_data['password'];
        $email = $request_data['email'];

        $sql = "update usuarios set 
                correo=:email,
                password=:password,
                telefono=:phone,
                nombre=:name 
                where id=:id";


        $query = $this->conn->prepare($sql);

        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->bindValue(":phone", $phone, PDO::PARAM_INT);
        $query->bindValue(":password", $password, PDO::PARAM_STR);
        $query->bindValue(":email", $email, PDO::PARAM_STR);



        $array = [
            "error" => "error al actualizr",
            "status" => "error"
        ];

        if ($query->execute()) {
            $array = [
                "data" => [
                    "id" => $id,
                    "name" => $name,
                    "phone" => $phone,
                    "password" => $password,
                    "email" => $email,
                ],

                "status" => "success"

            ];
        }

        json_encode($array);
    }


    //**********************************************
    public function delete()
    {

        $request_data = json_decode(file_get_contents("php://input"));

        $method = $_SERVER['REQUEST_METHOD'];

        if ($request_data) {
            $id = $request_data->id;
        }


        if ($method !== 'DELETE') :

            echo json_encode([
                "status" => 404,
                "message" => "Method Not Alowed"
            ]);

            exit;

        elseif (empty($id)) :

            echo json_encode([
                "status" => 404,
                "message" => "Id not Found"
            ]);

            exit;
        else :
            $sql = "delete from usuarios where id=:id";


            $query = $this->conn->prepare($sql);

            $query->bindValue(":id", $id, PDO::PARAM_INT);




            $array = [
                "error" => "error al borrar",
                "status" => "error"
            ];

            if ($query->execute()) {
                $array = [
                    "data" => [
                        "id" => $id,

                    ],

                    "status" => "success"

                ];
            }

            echo   json_encode($array);

        endif;
    }

    //************************************************* */
}
