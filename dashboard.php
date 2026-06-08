<?php
$conn = new mysqli("localhost", "root", "", "security_db");

$sql = "SELECT * FROM intrusion_events ORDER BY id DESC";
$result = $conn->query($sql);

$latest = $conn->query("SELECT * FROM intrusion_events ORDER BY id DESC LIMIT 1");
$latestRow = $latest->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="5">

<title>IoT Security Monitoring Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f1f5f9;
    font-family:Segoe UI, Arial, sans-serif;
    padding:20px;
}

h1{
    text-align:center;
    color:#1e293b;
    margin-bottom:30px;
}

.container{
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.card{
    width:280px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    text-align:center;
}

.card h3{
    color:#64748b;
    margin-bottom:15px;
}

.card p{
    font-size:28px;
    font-weight:bold;
    color:#0f172a;
}

.card1{
    border-left:6px solid #2563eb;
}

.card2{
    border-left:6px solid #dc2626;
}

.table-container{
    width:95%;
    margin:auto;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#2563eb;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f8fafc;
}

.safe{
    color:green;
    font-weight:bold;
}

.alert{
    color:red;
    font-weight:bold;
}

.footer{
    text-align:center;
    margin-top:20px;
    color:#64748b;
}

</style>

</head>

<body>

<h1>🔒 IoT Security Monitoring Dashboard</h1>

<div class="container">

    <div class="card card1">
        <h3>Latest Distance Detected</h3>
        <p>
            <?php
            echo $latestRow['Distance_Detected'] . " cm";
            ?>
        </p>
    </div>

    <div class="card card2">
        <h3>Latest Event Status</h3>
        <p>
            <?php
            echo $latestRow['Event_status'];
            ?>
        </p>
    </div>

</div>

<div class="table-container">

    <table>
        <tr>
            <th>Event ID</th>
            <th>Distance (cm)</th>
            <th>Status</th>
            <th>Timestamp</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo $row['Distance_Detected']; ?></td>

            <td>
                <?php
                if(strtolower($row['Event_status']) == "intruder detected"){
                    echo "<span class='alert'>🚨 ".$row['Event_status']."</span>";
                }else{
                    echo "<span class='safe'>✅ ".$row['Event_status']."</span>";
                }
                ?>
            </td>

            <td>
                <?php
                echo date("M d, Y H:i:s",
                strtotime($row['Timestamp']));
                ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

<div class="footer">
    Auto-refresh every 5 seconds
</div>

</body>
</html>