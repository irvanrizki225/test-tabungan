<?php
include_once 'koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM tb_report 
JOIN tb_transactions ON tb_report.AccountId = tb_transactions.AccountId
WHERE tb_report.id = '$id'" or die($con->error);
$result = $con->query($sql);

foreach ($result as $key => $value) {
    if ($value['DebitCreditStatus'] == 'D') {
        $data[] = [
            'TransactionDate' => $value['TransactionDate'],
            'Description' => $value['Description'],
            'Credit' => '-',
            'Debit' => 'Debit',
            'Amount' => $value['Amount']
        ];
    }else {
        $data[] = [
            'TransactionDate' => $value['TransactionDate'],
            'Description' => $value['Description'],
            'Credit' => 'Credit',
            'Debit' => '-',
            'Amount' => $value['Amount']
        ];
    }
}
// var_dump($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Report</title>
</head>
<body>

<div class="row">
    <div class="col-12">
        <h5>Report Nasabah </h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>AccountId</th>
                    <th>TransactionDate</th>
                    <th>Description</th>
                    <th>DebitCreditStatus</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $row) {
                    ?>
                <tr align="center">
                    <td><?php echo date('d-m-Y', strtotime($row['TransactionDate']))?></td>
                    <td><?php echo $row['Description']?></td>
                    <td><?php echo $row['Credit']?></td>
                    <td><?php echo $row['Debit']?></td>
                    <td><?php echo $row['Amount']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
