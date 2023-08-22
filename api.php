<?php
require('./config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/backendtest/api.php/getall') {

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

require('./return.php');