<?php
header('Content-Type: application/json');

// ================= DATABASE CONFIG =================
 $host = "sql302.infinityfree.com";      // Railway host
$username = "if0_40717004";                     // Railway username
$password = "teamca2025"; // Railway password
$database = "if0_40717004_teamcadb2025";                  // Database name

//$host = "caboose.proxy.rlwy.net";      // Railway host
//$username = "root";                     // Railway username
//$password = "VsLjiSqkLtYEHtslzodCcOFKJVKPYwUF"; // Railway password
//$database = "railway";                  // Database name

// CHANGE THESE DETAILS LATER
//$host = "207.180.214.131";
//$username = "iclonis";
//$password = "Iclonis@1979";
//$database = "its_work_details_2025";

// ================= CONNECT =================
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['status'=>'error','message'=>'Database Connection Failed: ' . $conn->connect_error]);
    exit;
}

// ================= GET FORM TYPE =================
$form_type = $_POST['form_type'] ?? '';

switch($form_type) {
    case 'pratibha':
        $table = "pratibha_applications";
        $fields = ['full_name','score','institution','phone','address'];
        break;
    case 'study':
        $table = "study_assistance_requests";
        $fields = ['full_name','course_stream','institution','phone','reason'];
        break;
    case 'medical':
        $table = "medical_assistance_requests";
        $fields = ['patient_name','hospital_name','amount_paid','contact_number','medical_reason'];
        break;
    case 'join':
        $table = "join_requests";
        $fields = ['full_name','email','contact_number','reason'];
        break;
    default:
        echo json_encode(['status'=>'error','message'=>'Invalid form submission']);
        exit;
}

// ================= GET FORM DATA =================
$data = [];
foreach($fields as $f){
    $data[$f] = trim($_POST[$f] ?? '');
}

// ================= BASIC VALIDATION =================
foreach($data as $value){
    if(empty($value)){
        echo json_encode(['status'=>'error','message'=>'All fields are required.']);
        exit;
    }
}

// ================= INSERT QUERY =================
$placeholders = implode(',', array_fill(0, count($fields), '?'));
$types = str_repeat('s', count($fields)); // all strings

$stmt = $conn->prepare("INSERT INTO $table (" . implode(',', $fields) . ") VALUES ($placeholders)");

if(!$stmt){
    echo json_encode(['status'=>'error','message'=>'Prepare Failed: '.$conn->error]);
    exit;
}

// Bind params dynamically
$stmt->bind_param($types, ...array_values($data));

if ($stmt->execute()) {
    echo json_encode(['status'=>'success','message'=>'Form submitted successfully!']);
} else {
    echo json_encode(['status'=>'error','message'=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
