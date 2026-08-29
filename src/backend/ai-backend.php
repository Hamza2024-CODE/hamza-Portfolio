<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// 1. Get the frontend payloads (Latest message + Conversation timeline array)
$input = json_decode(file_get_contents("php://input"), true);
$userText = isset($input['message']) ? trim($input['message']) : '';
$conversationHistory = isset($input['history']) ? $input['history'] : [];

if (empty($userText)) {
    echo json_encode(["reply" => "Please ask a question!"]);
    exit;
}

// 2. Custom function to safely load environment variables from local .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim(trim($value), '"\'');

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Load configurations from repository root
loadEnv(__DIR__ . '/../../.env');
$geminiApiKey = $_ENV['GEMINI_API_KEY'] ?? $_SERVER['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY') ?? '';
$groqApiKey = $_ENV['GROQ_API_KEY'] ?? $_SERVER['GROQ_API_KEY'] ?? getenv('GROQ_API_KEY') ?? '';

// 3. Import dynamic context instructions mapping
$instructionFilePath = __DIR__ . '/../../instruction.txt';

if (file_exists($instructionFilePath)) {
    $portfolioContext = file_get_contents($instructionFilePath);
} else {
    $portfolioContext = "You are the official AI portfolio assistant for Hamza Boubakar Seddik, Software Engineer & Multi-Platform Developer at MFEP Algeria. Contact: boubakarseddikh@gmail.com.";
}

$portfolioContext = trim($portfolioContext);

// 4. Fire cURL to Gemini API using X-goog-api-key header format with fast 5s timeout
if (!empty($geminiApiKey)) {
    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";
    
    $promptText = "System Instruction:\n" . $portfolioContext . "\n\nUser Question:\n" . $userText;
    
    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $promptText]
                ]
            ]
        ]
    ];
    
    $jsonPayload = json_encode($payload);
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "X-goog-api-key: " . $geminiApiKey,
        "Content-Length: " . strlen($jsonPayload)
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $data = json_decode($response, true);
    if ($httpCode === 200 && isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        echo json_encode(["reply" => trim($data['candidates'][0]['content']['parts'][0]['text'])]);
        exit;
    }
}

// 5. Try Groq API endpoint as backup
if (!empty($groqApiKey)) {
    $messagesPayload = [
        ["role" => "system", "content" => $portfolioContext],
        ["role" => "user", "content" => $userText]
    ];
    $postData = [
        "model" => "llama-3.3-70b-versatile",
        "messages" => $messagesPayload,
        "max_tokens" => 250,
        "temperature" => 0.5
    ];
    $jsonPayload = json_encode($postData);
    $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $groqApiKey,
        "Content-Type: application/json",
        "Content-Length: " . strlen($jsonPayload)
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = json_decode($response, true);
    if ($httpCode === 200 && isset($data['choices'][0]['message']['content'])) {
        echo json_encode(["reply" => trim($data['choices'][0]['message']['content'])]);
        exit;
    }
}

// 6. Intelligent Fallback Response
$fallbackReply = "Hello! I am Hamza Boubakar Seddik's AI Assistant. Hamza is a Software Engineer & Multi-Platform Developer at the Ministry of Vocational Training and Education (MFEP) in Algeria with 7+ years of experience in enterprise systems, Laravel, C#, ASP.NET, Java, REST APIs, and Cloud architecture. You can reach out directly via email at boubakarseddikh@gmail.com or phone at +213 779771993.";

echo json_encode(["reply" => $fallbackReply]);
exit;
