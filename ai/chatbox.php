<?php

include("../db.php");
include("../config.php");

$apiKey = $grok_api_key;

$msg = "";
$reply = "";


// CLEAR CHAT

if(isset($_POST['clear_chat'])){

    $conn->query("DELETE FROM chats");

    header("Location: chatbox.php");
    exit;
}



// MESSAGE

if(isset($_POST['msg'])){


$msg = trim($_POST['msg']);

$text = strtolower($msg);



// ================= DATABASE AI =================



// Complaints

if(
strpos($text,"complaint")!==false ||
strpos($text,"problem")!==false ||
strpos($text,"issue")!==false
){

$res=$conn->query(
"SELECT COUNT(*) c FROM complaints"
);

$row=$res->fetch_assoc();

$reply="📢 Total complaints registered: ".$row['c'];

}



// Pending

else if(strpos($text,"pending")!==false){


$res=$conn->query(
"SELECT COUNT(*) c 
FROM complaints 
WHERE status='Pending'"
);


$row=$res->fetch_assoc();


$reply="⏳ Pending complaints: ".$row['c'];

}



// Resolved

else if(
strpos($text,"resolved")!==false ||
strpos($text,"completed")!==false
){


$res=$conn->query(
"SELECT COUNT(*) c 
FROM complaints 
WHERE status='Resolved'"
);


$row=$res->fetch_assoc();


$reply="✅ Resolved complaints: ".$row['c'];

}



// Zone

else if(strpos($text,"zone")!==false ||
strpos($text,"area")!==false
){


$res=$conn->query(
"SELECT zone,status FROM zones LIMIT 5"
);


$reply="📍 Zone Status:\n";


while($r=$res->fetch_assoc()){

$reply .= 
$r['zone']." - ".$r['status']."\n";

}


}



// Tax

else if(strpos($text,"tax")!==false){


$res=$conn->query(
"SELECT SUM(total) total FROM taxes"
);


$row=$res->fetch_assoc();


$reply="💰 Total tax amount: ₹".$row['total'];

}



// Paid

else if(strpos($text,"paid")!==false){


$res=$conn->query(
"SELECT COUNT(*) c FROM taxes WHERE status='Paid'"
);


$row=$res->fetch_assoc();


$reply="✅ Paid tax records: ".$row['c'];

}




// Property

else if(
strpos($text,"property")!==false ||
strpos($text,"land")!==false
){


$res=$conn->query(
"SELECT COUNT(*) c FROM properties"
);


$row=$res->fetch_assoc();


$reply="🏠 Total properties: ".$row['c'];

}




// Citizen

else if(
strpos($text,"citizen")!==false ||
strpos($text,"people")!==false
){


$res=$conn->query(
"SELECT COUNT(*) c FROM citizen_login"
);


$row=$res->fetch_assoc();


$reply="👨‍👩‍👧 Citizens registered: ".$row['c'];

}





// Certificate

else if(strpos($text,"certificate")!==false){


$res=$conn->query(
"SELECT COUNT(*) c FROM certificates"
);


$row=$res->fetch_assoc();


$reply="📄 Certificate applications: ".$row['c'];

}




// Workers

else if(
strpos($text,"worker")!==false ||
strpos($text,"staff")!==false
){


$res=$conn->query(
"SELECT COUNT(*) c FROM workers"
);


$row=$res->fetch_assoc();


$reply="👷 Village workers: ".$row['c'];

}



// Announcement

else if(
strpos($text,"announcement")!==false
){


$res=$conn->query(
"SELECT title FROM announcements LIMIT 5"
);


$reply="📢 Announcements:\n";


while($r=$res->fetch_assoc()){

$reply .= "- ".$r['title']."\n";

}


}





// Expenses

else if(strpos($text,"expense")!==false){


$res=$conn->query(
"SELECT SUM(amount) total FROM expenses"
);


$row=$res->fetch_assoc();


$reply="💸 Total expenses: ₹".$row['total'];

}




// Predictions

else if(
strpos($text,"my village prediction")!==false ||
strpos($text,"my village future")!==false
){


$res=$conn->query(
"SELECT type,value FROM predictions"
);


$reply="🔮 Predictions:\n";


while($r=$res->fetch_assoc()){

$reply .=
$r['type']." : ".$r['value']."\n";

}

}

else if(
strpos($text,"hi")!==false ||
strpos($text,"hello")!==false ||
strpos($text,"hey")!==false ||
strpos($text,"hai")!==false
){

$reply =
"👋 Hello! Welcome to Smart Village AI Assistant.

I am here to help you with village services, complaints, taxes, certificates, agriculture, development and general questions.";

}



else if(
strpos($text,"good morning")!==false ||
strpos($text,"good afternoon")!==false ||
strpos($text,"good evening")!==false
){

$reply =
"😊 Good day! Hope you are doing well.
How can I assist you today?";

}



else if(
strpos($text,"how are you")!==false ||
strpos($text,"are you ok")!==false
){

$reply =
"🤖 I am active and ready to assist you.
Ask me anything about the Smart Village System.";

}



else if(
strpos($text,"who are you")!==false ||
strpos($text,"your name")!==false
){

$reply =
"I am Smart Village AI Assistant 🤖.

My role:
• Help citizens
• Provide village information
• Analyse complaints
• Explain services
• Support rural development";

}



else if(
strpos($text,"thank")!==false ||
strpos($text,"thanks")!==false
){

$reply =
"😊 You are welcome!
Feel free to ask me anything.";

}



else if(
strpos($text,"sorry")!==false
){

$reply =
"No problem 😊. How can I help you?";

}



else if(
strpos($text,"menu")!==false
){

$reply =
"📌 Available services:

🏠 Property Management
📢 Complaints
💰 Tax Management
📄 Certificates
👥 Citizen Details
👷 Workers
📍 Zone Monitoring
🌱 Agriculture
💧 Water
🏥 Health
🎓 Education
🔮 AI Predictions";

}


else if(
strpos($text,"contact")!==false ||
strpos($text,"phone")!==false
){

$reply =
"📞 Contact details are managed through village administration services.";

}




else if(
strpos($text,"bye")!==false ||
strpos($text,"exit")!==false
){

$reply =
"👋 Goodbye! Thank you for using Smart Village AI.";

}


else if(
strpos($text,"your services")!==false
){

$reply =
"🏡 Smart Village System connects citizens and administration digitally.

It manages:
• Complaints
• Taxes
• Properties
• Certificates
• Development activities";

}







// ================= GROK AI =================


else{


$url="https://api.groq.com/openai/v1/chat/completions";

$data=[

"model"=>"llama-3.3-70b-versatile",

"messages"=>[

[
"role"=>"system",
"content"=>
"You are Smart Village AI Assistant.
Answer shortly like a chatbot.
Give 3-5 lines maximum.
Use simple language.
Do not give long explanations unless asked.
Focus on village development, agriculture, complaints, taxes, health and education."
],

[
"role"=>"user",
"content"=>$msg
]

],

"temperature"=>0.5,

"max_tokens"=>150

];
$ch=curl_init($url);



curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);


curl_setopt($ch,CURLOPT_HTTPHEADER,[

"Content-Type: application/json",

"Authorization: Bearer ".$apiKey

]);



curl_setopt($ch,CURLOPT_POST,true);


curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));



$response=curl_exec($ch);



if(curl_errno($ch)){


$reply="CURL ERROR: ".curl_error($ch);


}

else{


$result=json_decode($response,true);



if(isset($result['choices'][0]['message']['content'])){


$reply=$result['choices'][0]['message']['content'];


}

else if(isset($result['error'])){


$reply="API ERROR: ".$result['error']['message'];


}

else{


$reply="AI not responding";

}


}


curl_close($ch);


}



// SAVE CHAT


$stmt=$conn->prepare(
"INSERT INTO chats(message,response) VALUES(?,?)"
);


$stmt->bind_param("ss",$msg,$reply);


$stmt->execute();


}




// LOAD CHAT


$chat=$conn->query(
"SELECT * FROM chats ORDER BY id ASC"
);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Chatbot</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Playfair Display', serif;
    background:white;
}

/* SIDEBAR */
.sidebar{
    width:250px;
    height:100vh;
    background:indigo;
    color:whitesmoke;
    padding:20px;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    padding:10px;
    margin:8px 0;
    font-size:larger;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

.sidebar ul li:hover{
    background:#382865;
}

/* LINKS */
.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:block;
}

/* MAIN */
/* MAIN */
.main{
    margin-left:265px;
    padding:10px 20px;
    width:calc(100% - 265px);
}


/* NAVBAR SMALL */
.navbar{
    width:100%;
    height:60px;
    background:#EFE9FF;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-radius:12px;
}


/* CHAT LAYOUT */
.chat-layout{
    display:block;
    width:100%;
    margin-top:15px;
}


/* CHAT SECTION BIGGER */
.chat-section{
    display:flex;
    flex-direction:column;
    height:calc(100vh - 170px);
    width:100%;
}



/* CHAT BOX BIG */
/* CHAT BOX */
.chat-box{

    height:100%;
    width:100%;

    overflow-y:auto;

    border:1px solid #ddd;
    border-radius:18px;

    padding:12px;

    background:white;

    display:flex;
    flex-direction:column;

    gap:6px;

}


/* BOT MESSAGE */
.bot{
    width:auto;
    max-width:65%;
    background:#EFE9FF;
    padding:8px 12px;
    border-radius:15px;
    margin-bottom:5px;
    font-size:14px;
    line-height:1.3;
    align-self:flex-start;
    white-space:pre-line;
}

/* USER MESSAGE */
.user{

    width:auto;
    max-width:80%;

    background:indigo;

    color:white;

    padding:8px 12px;

    border-radius:15px;

    margin-bottom:5px;

    font-size:14px;

    line-height:1.3;

    align-self:flex-end;

}


/* CHAT AREA HEIGHT */
.chat-section{
    height:calc(100vh - 110px);
   

}



/* INPUT SMALL LIKE REAL CHAT */
.input-area{

    margin-top:5px;
    margin-bottom:0;

}


.input-area input{

    padding:8px 15px;
    height:40px;
    font-size:14px;
    border-radius:20px;

}



.input-area button{

    padding:8px 18px;
    height:40px;
    border-radius:20px;

}
/* remove unused side-panel space */
.side-panel{
    display:none;
}

/* INPUT */
.input-area{
    display:flex;
    gap:10px;
    margin-top:15px;
}

.input-area input{
    flex:1;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
}

.input-area button{
    padding:12px 20px;
    border:none;
    border-radius:8px;
    background:indigo;
    color:white;
    cursor:pointer;
}

/* SIDE PANEL */
.side-panel{
    display:flex;
    flex-direction:column;
    gap:20px;
}

.card{
    background:#EFE9FF;
    padding:20px;
    border-radius:10px;
}


.clear-btn:hover{
    background:#bb2d3b;
}
.card h3{
    color:indigo;
    margin-bottom:10px;
}
.back-btn{
    display:block;
    background:indigo;
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:8px;
    font-size:14px;
}
/* RESPONSIVE */
/* ================= MOBILE VIEW FIX ================= */

@media(max-width:768px){


/* hide sidebar */
.sidebar{
    display:none;
}


/* full width */
.main{
    margin-left:0;
    width:100%;
    padding:10px;
}



/* navbar */
.navbar{

    height:auto;
    padding:12px;
    border-radius:10px;

}


.navbar h1{

    font-size:20px;
}



/* chat section */

.chat-section{

    height:calc(100vh - 130px);

}



/* chat box */

.chat-box{

    height:100%;
    padding:10px;
    border-radius:12px;

}



/* messages */

.bot{

    max-width:90%;
    font-size:13px;
    padding:10px;

}


.user{

    max-width:90%;
    font-size:13px;
    padding:10px;

}




/* input area */

.input-area{

    display:flex;
    gap:5px;
    margin-top:8px;

}


.input-area input{

    width:100%;
    height:42px;
    font-size:14px;

}



.input-area button{

    padding:10px;
    font-size:13px;
    border-radius:20px;

}



.clear-btn{

    display:none;

}



}


/* very small mobile */

@media(max-width:768px){

.back-btn{
    display:block;
    background:indigo;
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:8px;
    font-size:14px;
}

.navbar{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    gap:12px;
}

.navbar h1{
    font-size:18px;
    margin:0;
}



.chat-section{

    height:calc(100vh - 120px);

}


.input-area button{

    padding:8px;

}


.bot,.user{

    font-size:12px;

}

}
</style>

</head>

<body>

<div class="sidebar">
<ul>
    <li><a href="ai_dashboard.php">AI Dashboard</a></li>
    <li><a href="village_map.php">Village Map</a></li>
    <li><a href="complaint_analytics.php">Complaint Analytics</a></li>
    <li><a href="tax_analytics.php">Tax Analytics</a></li>
    <li><a href="chatbox.php">AI Chatbot</a></li>
    <li><a href="predictions.php">AI Insights</a></li>
    <li>
    <a href="../index.html">
    Logout
    </a>
    </li>
</ul>
</div>

<div class="main">

<div class="navbar">


<h1>AI Chatbot Assistant</h1>
<a href="ai_dashboard.php" class="back-btn">
Back
</a>

</div>

<div class="chat-layout">

<div class="chat-section">
<div class="chat-box" id="chatBox">

<div class="bot">
Hello 👋 I am your Smart Village Assistant.
</div>

<?php while($row = $chat->fetch_assoc()){ ?>

<div class="user">
<?php echo $row['message']; ?>
</div>

<div class="bot">
<?php echo $row['response']; ?>
</div>

<?php } ?>

</div>

<form method="POST" class="input-area" id="chatForm">

    <input type="text" name="msg" placeholder="Type your question..." required>

    <button type="submit">Send</button>

    <button type="submit"
            name="clear_chat"
            formnovalidate
            class="clear-btn">
        Clear
    </button>

</form>

</div>


</div>

</div>
</div> <!-- main -->

<script>

const form = document.getElementById("chatForm");

form.addEventListener("submit", function(){

    setTimeout(() => {

        let chatBox = document.getElementById("chatBox");
        chatBox.scrollTop = chatBox.scrollHeight;

    },100);

});

window.onload = function(){

    let chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;

};

</script>

</body>
</html>