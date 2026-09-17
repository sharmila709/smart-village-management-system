<?php
include("../db.php");
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$user = $conn->query("
SELECT name
FROM citizen_login
WHERE id='$user_id'
")->fetch_assoc();

$name = $user['name'];


$sql="
SELECT * FROM complaints 
WHERE citizen='$name'
ORDER BY date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Digital Village System - Complaint History</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:linear-gradient(135deg,indigo);
            padding:30px;
        }

        .container{
            max-width:1100px;
            margin:auto;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,0.2);
        }

        h1{
            text-align:center;
            color:indigo;
            margin-bottom:10px;
        }

        p{
            text-align:center;
            color:rgb(108, 45, 153);
            margin-bottom:25px;
        }

        .search-box{
            margin-bottom:20px;
        }

        .search-box input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:16px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:indigo;
            color:white;
            padding:12px;
        }

        td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        tr:hover{
            background:#f5f5f5;
        }

        .pending{ color:orange; font-weight:bold; }
        .progress{ color:blue; font-weight:bold; }
        .resolved{ color:green; font-weight:bold; }
        .closed{ color:red; font-weight:bold; }

        .view-btn{
            background:indigo;
            color:white;
            border:none;
            padding:8px 15px;
            border-radius:5px;
            cursor:pointer;
        }

        .view-btn:hover{
            background:#1976D2;
        }
        .back{
            display:inline-block;
            margin-bottom:10px;
            padding:8px 12px;
            background:indigo;
            color:white;
            text-decoration:none;
        }

        .footer{
            text-align:center;
            margin-top:20px;
            color:gray;
            font-size:14px;
        }
        /* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .container{
        width:100%;
        padding:15px;
        overflow:hidden;
    }


    h1{
        font-size:22px;
        line-height:30px;
    }


    p{
        font-size:15px;
    }


    .back{
        display:block;
        width:100%;
        text-align:center;
        border-radius:6px;
        margin-bottom:15px;
    }


    .search-box input{
        font-size:14px;
        padding:10px;
    }


    /* MOBILE TABLE SCROLL */
    table{
        display:block;
        width:100%;
        overflow-x:auto;
        white-space:nowrap;
    }


    th,td{
        padding:10px;
        font-size:13px;
    }


    .view-btn{
        padding:7px 12px;
        font-size:12px;
    }


    .footer{
        font-size:12px;
        line-height:20px;
    }

}
    </style>

</head>

<body style="font-family: 'Playfair Display', serif;">

<div class="container">
    <a class="back" href="dashboard.php">← Back</a>

    <h1>🏡 Smart Digital Village System</h1>
    <p>Complaint History</p>

    <div class="search-box">
        <input type="text" placeholder="Search by Complaint ID or Category...">
    </div>

    <table>

        <tr>
            <th>Complaint ID</th>
            <th>Category</th>
            <th>Date</th>
            <th>Citizen</th>
            <th>Status</th>
            <th>Assigned Officer</th>
            <th>Remarks</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['citizen']; ?></td>
            <td class="<?php echo strtolower(str_replace(' ','',$row['status'])); ?>">
                    <?php echo $row['status']; ?>
            </td>


            <td>
                <?php

                if($row['officer']=="Not Assigned" || empty($row['officer']))
                {
                    echo "<span style='color:orange;font-weight:bold;'>Not Assigned</span>";
                }
                else
                {
                    echo $row['officer'];
                }

                ?>
            </td>


            <td>
                <?php echo $row['description']; ?>
            </td>

        </tr>

        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>No complaints found</td></tr>";
                        }
        ?>

    </table>

    <div class="footer">
        © 2026 Smart Digital Village System | Complaint Management Portal
    </div>

</div>

</body>
</html>