<?php
include_once("koneksi.php");

$sql = "SELECT tb_transactions.AccountId, tb_nasabah.name 
FROM tb_transactions JOIN tb_nasabah ON tb_transactions.AccountId = tb_nasabah.AccountId 
GROUP BY tb_transactions.AccountId;";
$result = $con->query($sql);

foreach ($result as $row) {
    $point = array([
        'AccountId' => $row['AccountId'],
        'name' => $row['name'],
        'point' => 0
    ]);
}
// var_dump($point);
$sql_transaction = 'SELECT * FROM `tb_transactions`';
$result_transaction = $con->query($sql_transaction);

foreach ($result_transaction as $row_transaction) {
    foreach ($point as $key => $value) {
        if ($value['AccountId'] == $row_transaction['AccountId']) {

            if ($row_transaction['Amount'] <= 10000 && $row_transaction['Description'] == 'Beli Pulsa') {
                $total = 0;
            }elseif ($row_transaction['Amount'] > 10000 && $row_transaction['Amount'] <= 30000 && $row_transaction['Description'] == 'Beli Pulsa') {
                $total = $row_transaction['Amount']/1000; 
                $total_point = $total*1;
            }elseif ($row_transaction['Amount'] > 30000 && $row_transaction['Description'] == 'Beli Pulsa') {
                $total = $row_transaction['Amount']/1000;
                $total_point = $total*2; 
                $point[$key]['point'] += $total_point;
            }

            if ($row_transaction['Amount'] <= 10000 && $row_transaction['Description'] == 'Bayar Listrik') {
                $total = 0;
            }elseif ($row_transaction['Amount'] > 10000 && $row_transaction['Amount'] <= 30000 && $row_transaction['Description'] == 'Bayar Listrik') {
                $total = $row_transaction['Amount']/1000; 
                $total_point = $total*1;
                $point[$key]['point'] += $total_point;
            }elseif ($row_transaction['Amount'] > 30000 && $row_transaction['Description'] == 'Bayar Listrik') {
                $total = $row_transaction['Amount']/1000;
                $total_point = $total*2; 
                $point[$key]['point'] += $total_point;
            }
        }
    }
}
// var_dump($point);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>test</title>

</head>
<body>
    
<div class="row">
    <div class="col-12">
        <h5>Daftar Transaksi</h5>
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
                foreach ($result_transaction as $row) {
                    ?>
                <tr>
                    <td><?php echo $row['AccountId']?></td>
                    <td><?php echo $row['TransactionDate']?></td>
                    <td><?php echo $row['Description']?></td>
                    <td><?php echo $row['DebitCreditStatus']?></td>
                    <td><?php echo $row['Amount']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <h5>Daftar Poin</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>AccountId</th>
                    <th>Name</th>
                    <th>Total Point</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($point as $row) {
                    ?>
                <tr>
                    <td><?php echo $row['AccountId']?></td>
                    <td><?php echo $row['name']?></td>
                    <td><?php echo $row['point']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <h5>Report Transaction</h5>
        <form action="" method="post">
            <div class="row">
            <div class="col-3">
                    <label for="">Nasabah</label>
                    <select name="nasabah" id="" class="form-control">
                        <option value="">--Pilih--</option>
                        <?php
                        foreach ($result as $row) {
                            ?>
                            <option value="<?php echo $row['AccountId']?>"><?php echo $row['name']?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <label for="">Start Date</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">End Date</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="col-3">
                    <button type="submit" name="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr align="center">
                    <th>AccountId</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_report = "select * from tb_report";
                $result_report = $con->query($sql_report);

                foreach ($result_report as $row) {
                ?>
                <tr align="center">
                    <td><?php echo $row['AccountId']?></td>
                    <td><?php echo $row['StartDate']?></td>
                    <td><?php echo $row['EndDate']?></td>
                    <td>
                        <a href="report.php?id=<?php echo $row['id']?>" class="btn btn-primary">
                            Detail
                        </a>
                    </td>
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

<?php
if (isset($_POST['submit'])) {
    $nasabah = $_POST['nasabah'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $sql = "INSERT INTO tb_report (AccountId, StartDate, EndDate) VALUES ('$nasabah', '$start_date', '$end_date')";
    $query = mysqli_query($con, $sql);
    if ($query) {
        echo "<script>alert('Berhasil')</script>";
    }else{
        echo "<script>alert('Gagal')</script>";
    }
}


    ?>



    