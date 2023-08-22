<?php
require('./config.php');

// Endpoint para obter os detalhes de todas as campanhas
if ($_SERVER['REQUEST_URI'] === '/backendtest/api.php/all') {
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $sql = $pdo->query("SELECT * FROM campaigns");
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            foreach($data as $item){
                $response['result'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'campaingBudget' => $item['campaignbudget'],
                    'startDate' => $item['startdate'],
                    'endDate' => $item['enddate'],
                    'urlMedia' => $item['media'],
                    'status' => $item['status'],
                ];
            };
        }
    } else {
        $response['error'] = 'Method Not Allowed';
    }
}

if (strpos($_SERVER['REQUEST_URI'], '/backendtest/api.php/get') !== false) {
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $id = filter_input(INPUT_GET, 'id');
        if($id) {
            $sql = $pdo->prepare('SELECT * FROM campaigns WHERE id = :id');
            $sql->bindValue(':id', $id);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                $response['result'][] = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'campaingBudget' => $data['campaignbudget'],
                    'startDate' => $data['startdate'],
                    'endDate' => $data['enddate'],
                    'urlMedia' => $data['media'],
                    'status' => $data['status'],
                ];
            } else{
                $response['error'] = 'ID Not Found';
            };
        } else {
            $response['error'] = 'ID Not Sended';
        };
    } else {
        $response['error'] = 'Method Not Allowed';
    }
};

require('./return.php');