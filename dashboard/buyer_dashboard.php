<?php
session_start();
include '../config/db_connect.php';
include '../buyer/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buyer Dashboard | AgriCycle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chat-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      cursor: pointer;
    }
    .chat-container {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 350px;
      height: 500px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 999;
    }
    .chat-header {
      background: #4a90e2;
      color: #fff;
      text-align: center;
      padding: 12px;
      font-size: 18px;
      font-weight: bold;
    }
    .chat-box {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
      background: #f3f6fd;
      display: flex;
      flex-direction: column;
    }
    .message {
      padding: 10px;
      margin-bottom: 8px;
      border-radius: 10px;
      max-width: 80%;
      animation: fadeIn 0.3s ease-in-out;
    }
    .bot { background: #e1f5fe; align-self: flex-start; }
    .user { background: #fff9c4; align-self: flex-end; }
    .chat-input {
      display: flex;
      padding: 10px;
      border-top: 1px solid #ccc;
      background: #fff;
    }
    input {
      flex: 1;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      padding: 8px 10px;
      margin-left: 8px;
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .options {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 8px;
    }
    .option-btn {
      padding: 6px 10px;
      background: #1976d2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <h2 class="text-primary">Welcome, Buyer!</h2>
  <p class="text-muted">Explore waste listings, connect with farmers, and contribute to sustainability.</p>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card shadow-lg text-center border-0">
        <div class="card-body">
          <i class="bi bi-cart display-4 text-primary"></i>
          <h5 class="mt-3">Marketplace</h5>
          <p>Browse and purchase waste materials.</p>
          <a href="../marketplace/index.php" class="btn btn-outline-primary">Go to Marketplace</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-lg text-center border-0">
        <div class="card-body">
          <i class="bi bi-heart display-4 text-danger"></i>
          <h5 class="mt-3">Wishlist</h5>
          <p>Save items for later purchase.</p>
          <a href="../wishlist.php" class="btn btn-outline-danger">View Wishlist</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-lg text-center border-0">
        <div class="card-body">
          <i class="bi bi-envelope-check display-4 text-success"></i>
          <h5 class="mt-3">Order History</h5>
          <p>Track your previous purchases.</p>
          <a href="../orders.php" class="btn btn-outline-success">View Orders</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-lg text-center border-0">
        <div class="card-body">
          <i class="bi bi-people-fill display-4 text-info"></i>
          <h5 class="mt-3">Community Forum</h5>
          <p>Join the AgriCycle community.</p>
          <a href="../community/community_forum.php" class="btn btn-outline-info">Visit Forum</a>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid mt-4">
  <div class="card shadow-lg border-0 p-4">
    <h2 class="text-success mb-4 text-center">♻ Did You Know?</h2>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>1. Recycling one aluminum can</strong> saves enough energy to run a TV for 3 hours!
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>2. Buying products made from recycled materials</strong> helps reduce landfill waste and conserves natural resources.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>3. Every ton of recycled paper</strong> saves 17 trees, 7,000 gallons of water, and 4,000 kWh of energy.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>4. Recycled plastic</strong> can be used to create clothing, bags, and even furniture — shop smart!
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>5. E-waste contains valuable metals</strong> like gold, silver, and copper — always donate or recycle your gadgets.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>6. Choosing local & sustainable products</strong> lowers your carbon footprint and supports the community.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>7. Compostable packaging</strong> breaks down naturally and reduces plastic pollution — check before you buy!
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>8. You can earn rewards</strong> by recycling correctly — check out local programs and apps that offer points or cashback.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>9. Reusable bags</strong> can replace hundreds of single-use plastic bags every year.
        </div>
      </div>

      <div class="col">
        <div class="alert alert-info shadow-sm">
          <strong>10. Recycling one glass bottle</strong> saves enough energy to power a computer for 25 minutes.
        </div>
      </div>
    </div>
  </div>
</div>

  </div>
</div>

<!-- Chatbot Toggle Button -->
<button class="chat-toggle" onclick="toggleChat()"><i class="bi bi-chat-dots-fill"></i></button>

<!-- Chatbot Container -->
<div class="chat-container" id="chatbot">
  <div class="chat-header">Buyer Chatbot 🤖</div>
  <div class="chat-box" id="chat-box">
    <div class="message bot">Hello Buyer! What’s your name? 👤</div>
  </div>
  <div class="chat-input">
    <input type="text" id="user-input" placeholder="Type or speak..." />
    <button onclick="sendMessage()">Send</button>
    <button onclick="startVoice()" title="Speak"><i class="bi bi-mic-fill"></i></button>
  </div>
</div>

<!-- JS -->
<script>
  
  const responses = {
    "how to buy": "🛍 How to Buy:\n1. Go to 'Marketplace'.\n2. Browse products.\n3. Click 'Buy Product'.\n4. Fill details and confirm.",
    "wishlist": "💖 Wishlist:\n- Save favorite items and access anytime.",
    "view history": "📜 View History:\n- See past orders in 'Order History'.",
    "community forum": "💬 Forum:\n- Share and learn with others."
  };

  let buyerName = "";

  function toggleChat() {
    const chatbot = document.getElementById("chatbot");
    chatbot.style.display = chatbot.style.display === "flex" ? "none" : "flex";
  }

  function sendMessage() {
    const input = document.getElementById("user-input");
    const text = input.value.trim();
    if (!text) return;
    appendMessage(text, "user");
    input.value = "";

    setTimeout(() => {
      const userMessage = text.toLowerCase();
      if (!buyerName) {
        buyerName = text;
        const reply = `Hi ${buyerName}! 😊 Choose an option below 👇`;
        appendMessage(reply, "bot");
        speak(reply);
        setTimeout(showOptions, 500); // 💡 Delay to ensure smooth UI
      } else if (responses[userMessage]) {
        appendMessage(responses[userMessage], "bot");
        speak(responses[userMessage]);
        askMoreHelp();
      } else {
        const msg = "Please choose a valid option below 👇";
        appendMessage(msg, "bot");
        speak(msg);
        showOptions();
      }
    }, 500);
  }

  function appendMessage(text, sender) {
    const chatBox = document.getElementById("chat-box");
    const msg = document.createElement("div");
    msg.className = `message ${sender}`;
    msg.textContent = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function showOptions() {
    const chatBox = document.getElementById("chat-box");
    const optionsDiv = document.createElement("div");
    optionsDiv.className = "options";

    Object.keys(responses).forEach(opt => {
      const btn = document.createElement("button");
      btn.className = "option-btn";
      btn.textContent = opt.charAt(0).toUpperCase() + opt.slice(1);
      btn.onclick = () => {
        appendMessage(btn.textContent, "user");
        appendMessage(responses[opt], "bot");
        speak(responses[opt]);
        askMoreHelp();
      };
      optionsDiv.appendChild(btn);
    });

    chatBox.appendChild(optionsDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function askMoreHelp() {
    setTimeout(() => {
      const msg = "Would you like more help?";
      appendMessage(msg, "bot");
      speak(msg);

      const chatBox = document.getElementById("chat-box");
      const optionsDiv = document.createElement("div");
      optionsDiv.className = "options";

      const yes = document.createElement("button");
      yes.className = "option-btn";
      yes.textContent = "Yes";
      yes.onclick = () => showOptions();

      const no = document.createElement("button");
      no.className = "option-btn";
      no.textContent = "No";
      no.onclick = () => {
        const bye = "Thanks for chatting! 👋";
        appendMessage(bye, "bot");
        speak(bye);
      };

      optionsDiv.appendChild(yes);
      optionsDiv.appendChild(no);
      chatBox.appendChild(optionsDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
    }, 1000);
  }

  function speak(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    speechSynthesis.speak(utterance);
  }

  function startVoice() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      alert("Sorry, your browser does not support voice recognition.");
      return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "en-US";
    recognition.interimResults = true;

    let finalTranscript = "";

    recognition.onresult = function(event) {
      let interimTranscript = "";
      for (let i = event.resultIndex; i < event.results.length; ++i) {
        if (event.results[i].isFinal) {
          finalTranscript += event.results[i][0].transcript;
        } else {
          interimTranscript += event.results[i][0].transcript;
        }
      }
      document.getElementById("user-input").value = finalTranscript + interimTranscript;
    };

    recognition.start();
  }
</script>

</body>
</html>
