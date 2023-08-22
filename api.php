<?php
require('./config.php');

// Endpoint para obter os detalhes de todas as campanhas
if ($_SERVER['REQUEST_URI'] === '/backendtest/api.php/allcampaings') {
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

// Endpoint para obter os detalhes de uma campanha especifica
if (strpos($_SERVER['REQUEST_URI'], '/backendtest/api.php/getcampaing') !== false) {
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
                    'campaignpublic' => $data['campaignpublic'],
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

// Endpoint para inserir uma nova campanha
if ($_SERVER['REQUEST_URI'] === '/backendtest/api.php/campaigns') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requiredFields = ['name', 'campaignbudget', 'campaignpublic', 'startdate', 'enddate'];
        $filteredData = filterInputFields(INPUT_POST, $requiredFields);

        if ($filteredData) {
            insertCampaignIntoDatabase($pdo, $filteredData);
            $id = $pdo->lastInsertId();
            $response['result'] = createResponseArray($id, $filteredData);
        } else {
            $response['error'] = 'Fields Not Sended or Missing';
        }
    } else {
        $response['error'] = 'Method Not Allowed';
    }
}

function filterInputFields($inputType, $fields) {
    $filteredData = [];

    foreach ($fields as $field) {
        $value = filter_input($inputType, $field);
        if (!$value) {
            return false;
        }
        $filteredData[$field] = $value;
    }

    return $filteredData;
}

function insertCampaignIntoDatabase($pdo, $data) {
    $sql = $pdo->prepare
    ('INSERT INTO campaigns (name, campaignbudget, campaignpublic, startdate, enddate) 
    VALUES (:name, :campaignbudget, :campaignpublic, :startdate, :enddate)');
    
    foreach ($data as $field => $value) {
        $sql->bindValue(':' . $field, $value);
    }
    
    $sql->execute();
}

function createResponseArray($id, $data) {
    return [
        'id' => $id,
        'name' => $data['name'],
        'campaignBudget' => $data['campaignbudget'],
        'campaignpublic' => $data['campaignpublic'],
        'startDate' => $data['startdate'],
        'endDate' => $data['enddate'],
        'status' => 'active',
    ];
}

require('./return.php');