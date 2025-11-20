<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VinFast AI - Tư vấn xe thông minh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 100px;
        }

        .chat-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            height: 80vh;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: linear-gradient(45deg, #1464F4, #0040FF);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .chat-header h1 {
            margin-bottom: 5px;
            font-size: 24px;
        }

        .chat-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            padding: 15px 20px;
            border-radius: 20px;
            word-wrap: break-word;
        }

        .message.bot .message-content {
            background: white;
            border: 2px solid #e9ecef;
            margin-left: 10px;
        }

        .message.user .message-content {
            background: linear-gradient(45deg, #1464F4, #0040FF);
            color: white;
            margin-right: 10px;
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .bot-avatar {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
        }

        .user-avatar {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
        }

        .chat-input-container {
            padding: 20px;
            background: white;
            border-top: 1px solid #e9ecef;
        }

        .chat-input-form {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .chat-input:focus {
            border-color: #1464F4;
        }

        .send-btn {
            padding: 15px 25px;
            background: linear-gradient(45deg, #1464F4, #0040FF);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }

        .send-btn:hover {
            transform: scale(1.05);
        }

        .send-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .typing-indicator {
            display: none;
            margin-bottom: 15px;
        }

        .typing-dots {
            display: inline-block;
            width: 60px;
            height: 20px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            margin-left: 50px;
            position: relative;
            padding: 5px;
        }

        .typing-dots::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1464F4;
            animation: typing 1.4s infinite ease-in-out both;
        }

        @keyframes typing {
            0%, 80%, 100% { transform: translateY(-50%) scale(0); }
            40% { transform: translateY(-50%) scale(1); }
        }

        .car-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #e9ecef;
            transition: transform 0.3s;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .car-name {
            font-weight: bold;
            color: #1464F4;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .car-details {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }

        .car-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 16px;
            margin-top: 8px;
        }

        .quick-suggestions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .suggestion-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .suggestion-btn:hover {
            background: #1464F4;
            color: white;
            border-color: #1464F4;
        }

        @media (max-width: 768px) {
            .chat-container {
                margin: 10px;
                height: 85vh;
            }
            
            .message-content {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h1><i class="fas fa-robot"></i> VinFast AI Assistant</h1>
            <p>Tư vấn xe điện thông minh - Hỏi tôi về bất kỳ dòng xe nào!</p>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-avatar bot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    Xin chào! Tôi là AI Assistant của VinFast. Tôi có thể giúp bạn:
                    <br>• Tìm xe theo màu sắc và mức giá
                    <br>• Tư vấn thông số kỹ thuật
                    <br>• So sánh các dòng xe
                    <br>• Đề xuất xe phù hợp với nhu cầu
                    <br><br>Bạn muốn tìm xe như thế nào?
                    
                    <div class="quick-suggestions">
                        <div class="suggestion-btn" onclick="sendQuickMessage('Tôi muốn tìm xe màu đỏ dưới 500 triệu')">Xe đỏ dưới 500tr</div>
                        <div class="suggestion-btn" onclick="sendQuickMessage('Xe 5 chỗ tầm 700 triệu')">Xe 5 chỗ 700tr</div>
                        <div class="suggestion-btn" onclick="sendQuickMessage('Thông số VF8')">Thông số VF8</div>
                        <div class="suggestion-btn" onclick="sendQuickMessage('So sánh VF5 và VF6')">So sánh xe</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots"></div>
        </div>
        
        <div class="chat-input-container">
            <form class="chat-input-form" id="chatForm">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Nhập câu hỏi của bạn..." 
                    autocomplete="off"
                >
                <button type="submit" class="send-btn" id="sendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const typingIndicator = document.getElementById('typingIndicator');

        // Gửi tin nhắn
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message) {
                sendMessage(message);
                chatInput.value = '';
            }
        });

        function sendMessage(message) {
            // Hiển thị tin nhắn người dùng
            addMessage(message, 'user');
            
            // Vô hiệu hóa input và hiển thị typing indicator
            chatInput.disabled = true;
            sendBtn.disabled = true;
            showTypingIndicator();
            
            // Gửi request đến AI
            fetch('chatbot_ai.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                hideTypingIndicator();
                if (data.success) {
                    addMessage(data.response, 'bot');
                } else {
                    addMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideTypingIndicator();
                addMessage('Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối internet.', 'bot');
            })
            .finally(() => {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            });
        }

        function addMessage(content, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            
            if (type === 'bot') {
                messageDiv.innerHTML = `
                    <div class="message-avatar bot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">${content}</div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="message-content">${content}</div>
                    <div class="message-avatar user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                `;
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function showTypingIndicator() {
            typingIndicator.style.display = 'block';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        function sendQuickMessage(message) {
            sendMessage(message);
        }

        // Hàm xử lý nút "Xem thêm"
        function showMoreCars() {
            sendMessage("Xem thêm xe");
        }

        // Auto-focus vào input
        chatInput.focus();
    </script>
</body>
</html>
