<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Farmer Chatbot</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #81FBB8, #28C76F);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .chat-container {
      width: 400px;
      height: 550px;
      background: white;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .chat-header {
      background: linear-gradient(135deg, #43cea2, #185a9d);
      padding: 15px;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      color: white;
    }
    .chat-box {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      background: #f1f8e9;
    }
    .message {
      max-width: 80%;
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 10px;
      font-size: 14px;
      animation: fadeIn 0.5s;
    }
    .bot {
      background: #e8f5e9;
      align-self: flex-start;
    }
    .user {
      background: #fff3e0;
      align-self: flex-end;
    }
    .chat-input {
      display: flex;
      padding: 12px;
      border-top: 1px solid #ddd;
      background: white;
    }
    input {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      outline: none;
    }
    button {
      padding: 10px 15px;
      margin-left: 10px;
      border: none;
      background: #388e3c;
      color: white;
      cursor: pointer;
      border-radius: 5px;
      transition: 0.3s;
    }
    button:hover {
      background: #2e7d32;
    }
    .options {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }
    .option-btn {
      padding: 8px 12px;
      background: #00796b;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }
    .option-btn:hover {
      background: #004d40;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="chat-container">
    <div class="chat-header">Farmer Chatbot 🌾🤝</div>
    <div class="chat-box" id="chat-box">
      <div class="message bot">Hello Farmer! What's your name? 👩‍🌾</div>
    </div>
    <div class="chat-input">
      <input type="text" id="user-input" placeholder="Type your name..." />
      <button onclick="sendMessage()">Send</button>
    </div>
  </div>

  <script>
    let farmerName = "";
    let responses = {
      "Sell Products": "You can list your crops with its quantity,price,description,cotact details and produce on our marketplace to reach buyers. 🧺",
      "Apply for Government Schemes": "Here’s how you can apply for the latest schemes available for farmers. 🏛",
      "Join Community Forum": "Our community forum lets you connect with other farmers, share tips, and get support. 💬",
      "Farmer Chatbot": "I’m here to help you with farming, selling, schemes, and more. 🌿"
    };

    function sendMessage() {
      let userInput = document.getElementById("user-input").value;
      if (userInput.trim() === "") return;

      appendMessage(userInput, "user");
      document.getElementById("user-input").value = "";
      setTimeout(() => botResponse(userInput), 1000);
    }

    function appendMessage(text, sender) {
      let chatBox = document.getElementById("chat-box");
      let messageDiv = document.createElement("div");
      messageDiv.classList.add("message", sender);
      messageDiv.textContent = text;
      chatBox.appendChild(messageDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function botResponse(userText) {
      if (!farmerName) {
        farmerName = userText;
        appendMessage(Hi ${farmerName}! 🌿 How can I assist you today?, "bot");
        showOptions();
        return;
      }

      appendMessage(You selected: ${userText}, "user");

      if (responses[userText]) {
        appendMessage(responses[userText], "bot");
        setTimeout(() => askForMoreHelp(), 1000);
      } else {
        appendMessage("I didn't understand that. Please choose one of the options below. 🤔", "bot");
        showOptions();
      }
    }

    function askForMoreHelp() {
      appendMessage("Do you need more help? 🙋", "bot");
      let chatBox = document.getElementById("chat-box");
      let optionsDiv = document.createElement("div");
      optionsDiv.classList.add("options");

      let yesBtn = document.createElement("button");
      yesBtn.textContent = "Yes";
      yesBtn.classList.add("option-btn");
      yesBtn.onclick = () => showOptions();

      let noBtn = document.createElement("button");
      noBtn.textContent = "No";
      noBtn.classList.add("option-btn");
      noBtn.onclick = () => appendMessage("Goodbye, and happy farming! 🌾👋", "bot");

      optionsDiv.appendChild(yesBtn);
      optionsDiv.appendChild(noBtn);
      chatBox.appendChild(optionsDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function showOptions() {
      let chatBox = document.getElementById("chat-box");
      let optionsDiv = document.createElement("div");
      optionsDiv.classList.add("options");

      Object.keys(responses).forEach(option => {
        let btn = document.createElement("button");
        btn.textContent = option;
        btn.classList.add("option-btn");
        btn.onclick = () => botResponse(option);
        optionsDiv.appendChild(btn);
      });

      chatBox.appendChild(optionsDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
    }
  </script>
</body>
</html>