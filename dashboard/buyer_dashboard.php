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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary-green: #2e7d32;
      --light-green: #81c784;
      --dark-green: #1b5e20;
      --earth-brown: #5d4037;
      --sun-yellow: #ffd54f;
      --sky-blue: #4fc3f7;
      --harvest-orange: #fb8c00;
    }
    
    body {
      background-color: #f5f5f5;
      background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1000');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      background-blend-mode: overlay;
      background-color: rgba(245, 245, 245, 0.9);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container {
      max-width: 1400px;
    }
    
    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      background-color: rgba(255, 255, 255, 0.95);
      position: relative;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-green), var(--light-green));
    }
    
    .card-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--primary-green), var(--light-green));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    h2 {
      color: var(--dark-green);
      font-weight: 700;
      position: relative;
      padding-bottom: 10px;
    }
    
    h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-green), var(--light-green));
    }
    
    .btn-primary {
      background-color: var(--primary-green);
      border-color: var(--primary-green);
      transition: all 0.3s;
    }
    
    .btn-primary:hover {
      background-color: var(--dark-green);
      border-color: var(--dark-green);
      transform: translateY(-2px);
    }
    
    .btn-outline-primary {
      color: var(--primary-green);
      border-color: var(--primary-green);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-green);
      border-color: var(--primary-green);
    }
    
    .alert-info {
      background-color: #e8f5e9;
      border-left: 5px solid var(--primary-green);
      color: #2e7d32;
      transition: all 0.3s;
    }
    
    .alert-info:hover {
      transform: translateX(5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Animated background elements */
    .leaf-decoration {
      position: absolute;
      opacity: 0.1;
      z-index: -1;
    }
    
    .leaf-1 {
      top: 10%;
      left: 5%;
      animation: float 6s ease-in-out infinite;
    }
    
    .leaf-2 {
      top: 30%;
      right: 5%;
      animation: float 8s ease-in-out infinite;
    }
    
    .leaf-3 {
      bottom: 20%;
      left: 10%;
      animation: float 7s ease-in-out infinite;
    }
    
    @keyframes float {
      0% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
      100% { transform: translateY(0) rotate(0deg); }
    }
    
    /* Chatbot styles with agriculture theme */
    .chat-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--primary-green);
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
      box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
      transition: all 0.3s;
    }
    
    .chat-toggle:hover {
      transform: scale(1.1);
      background: var(--dark-green);
    }
    
    .chat-container {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 350px;
      height: 500px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 999;
      transform: translateY(20px);
      opacity: 0;
      transition: all 0.3s ease-out;
    }
    
    .chat-container.show {
      display: flex;
      transform: translateY(0);
      opacity: 1;
    }
    
    .chat-header {
      background: linear-gradient(90deg, var(--primary-green), var(--light-green));
      color: #fff;
      text-align: center;
      padding: 12px;
      font-size: 18px;
      font-weight: bold;
      position: relative;
    }
    
    .chat-header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 20px;
      width: 20px;
      height: 20px;
      background: #fff;
      transform: rotate(45deg);
      z-index: 1;
    }
    
    .chat-box {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      background: #f5f5f5;
      display: flex;
      flex-direction: column;
      background-image: url('https://www.transparenttextures.com/patterns/rice-paper.png');
    }
    
    .message {
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 15px;
      max-width: 80%;
      animation: fadeIn 0.4s ease-out;
      position: relative;
      font-size: 0.95rem;
      line-height: 1.4;
    }
    
    .bot { 
      background: #fff;
      align-self: flex-start;
      border-top-left-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      border-left: 3px solid var(--primary-green);
    }
    
    .user { 
      background: linear-gradient(135deg, var(--light-green), var(--primary-green));
      color: white;
      align-self: flex-end;
      border-top-right-radius: 5px;
    }
    
    .chat-input {
      display: flex;
      padding: 15px;
      border-top: 1px solid #e0e0e0;
      background: #fff;
    }
    
    input {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid #e0e0e0;
      border-radius: 25px;
      outline: none;
      transition: all 0.3s;
    }
    
    input:focus {
      border-color: var(--primary-green);
      box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
    }
    
    button {
      padding: 10px 15px;
      margin-left: 10px;
      background: var(--primary-green);
      color: #fff;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    button:hover {
      background: var(--dark-green);
      transform: translateY(-2px);
    }
    
    .options {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 10px;
    }
    
    .option-btn {
      padding: 8px 15px;
      background: var(--primary-green);
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 0.85rem;
      transition: all 0.3s;
    }
    
    .option-btn:hover {
      background: var(--dark-green);
      transform: translateY(-2px);
    }
    
    @keyframes fadeIn {
      from { 
        opacity: 0; 
        transform: translateY(10px); 
      }
      to { 
        opacity: 1; 
        transform: translateY(0); 
      }
    }
    
    /* Stats cards with animations */
    .stat-card {
      border-radius: 15px;
      padding: 20px;
      color: white;
      text-align: center;
      margin-bottom: 20px;
      transition: all 0.4s;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        transparent,
        rgba(255,255,255,0.1),
        transparent
      );
      transform: rotate(30deg);
      transition: all 0.5s;
    }
    
    .stat-card:hover::before {
      animation: shine 1.5s;
    }
    
    @keyframes shine {
      100% {
        left: 150%;
      }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .card {
        margin-bottom: 20px;
      }
      
      .chat-container {
        width: 300px;
        height: 450px;
      }
    }
    
    /* Floating animation for cards */
    .floating-card {
      animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    
    /* Pulse animation for important elements */
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.4); }
      70% { box-shadow: 0 0 0 10px rgba(46, 125, 50, 0); }
      100% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0); }
    }
    
    /* Eco tips section */
    .eco-tip {
      position: relative;
      padding-left: 30px;
      margin-bottom: 15px;
    }
    
    .eco-tip::before {
      content: '🌱';
      position: absolute;
      left: 0;
      top: 0;
      font-size: 1.2rem;
    }
    
    /* Watermark effect */
    .watermark {
      position: absolute;
      opacity: 0.03;
      font-size: 10rem;
      font-weight: bold;
      color: var(--primary-green);
      z-index: -1;
      user-select: none;
    }
  </style>
</head>
<body>

<!-- Decorative elements -->
<div class="leaf-decoration leaf-1">
  <svg width="100" height="100" viewBox="0 0 100 100">
    <path fill="var(--primary-green)" d="M50,0 C70,30 90,50 100,80 C80,90 60,100 30,90 C10,80 0,60 0,30 C10,10 30,0 50,0 Z" />
  </svg>
</div>

<div class="leaf-decoration leaf-2">
  <svg width="120" height="120" viewBox="0 0 100 100">
    <path fill="var(--light-green)" d="M50,0 C70,20 90,40 90,70 C70,90 40,100 10,90 C0,60 10,30 30,10 C40,0 50,0 50,0 Z" />
  </svg>
</div>

<div class="container mt-4 animate__animated animate__fadeIn">
  <div class="text-center mb-5">
    <h2 class="text-primary">Welcome, Buyer!</h2>
    <p class="lead text-muted">Explore waste listings, connect with farmers, and contribute to sustainability.</p>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
      <div class="card shadow-lg text-center h-100 floating-card">
        <div class="card-body">
          <div class="card-icon">
            <i class="bi bi-cart"></i>
          </div>
          <h5 class="mt-3">Marketplace</h5>
          <p class="text-muted">Browse and purchase waste materials.</p>
          <a href="../marketplace/index.php" class="btn btn-primary mt-2">Go to Marketplace</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
      <div class="card shadow-lg text-center h-100 floating-card">
        <div class="card-body">
          <div class="card-icon">
            <i class="bi bi-heart"></i>
          </div>
          <h5 class="mt-3">Wishlist</h5>
          <p class="text-muted">Save items for later purchase.</p>
          <a href="../wishlist.php" class="btn btn-outline-primary mt-2">View Wishlist</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
      <div class="card shadow-lg text-center h-100 floating-card">
        <div class="card-body">
          <div class="card-icon">
            <i class="bi bi-envelope-check"></i>
          </div>
          <h5 class="mt-3">Order History</h5>
          <p class="text-muted">Track your previous purchases.</p>
          <a href="../orders.php" class="btn btn-outline-primary mt-2">View Orders</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
      <div class="card shadow-lg text-center h-100 floating-card">
        <div class="card-body">
          <div class="card-icon">
            <i class="bi bi-people-fill"></i>
          </div>
          <h5 class="mt-3">Community Forum</h5>
          <p class="text-muted">Join the AgriCycle community.</p>
          <a href="../community/community_forum.php" class="btn btn-outline-primary mt-2">Visit Forum</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Eco Tips Section -->
  <div class="card shadow-lg border-0 mb-5 animate__animated animate__fadeIn">
    <div class="card-body p-4">
      <h2 class="text-center mb-4 text-success">♻ Sustainable Farming Tips</h2>
      
      <div class="row">
        <div class="col-md-6">
          <div class="eco-tip animate__animated animate__fadeInLeft">
            <h5>Composting Benefits</h5>
            <p class="text-muted">Turn organic waste into nutrient-rich compost to improve soil health and reduce landfill waste.</p>
          </div>
          
          <div class="eco-tip animate__animated animate__fadeInLeft" style="animation-delay: 0.1s">
            <h5>Water Conservation</h5>
            <p class="text-muted">Collect rainwater and use drip irrigation to minimize water usage in your farming practices.</p>
          </div>
          
          <div class="eco-tip animate__animated animate__fadeInLeft" style="animation-delay: 0.2s">
            <h5>Crop Rotation</h5>
            <p class="text-muted">Rotate crops seasonally to maintain soil fertility and reduce pest problems naturally.</p>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="eco-tip animate__animated animate__fadeInRight">
            <h5>Natural Pest Control</h5>
            <p class="text-muted">Use companion planting and beneficial insects instead of chemical pesticides.</p>
          </div>
          
          <div class="eco-tip animate__animated animate__fadeInRight" style="animation-delay: 0.1s">
            <h5>Renewable Energy</h5>
            <p class="text-muted">Consider solar panels or wind turbines to power your farming operations sustainably.</p>
          </div>
          
          <div class="eco-tip animate__animated animate__fadeInRight" style="animation-delay: 0.2s">
            <h5>Local Markets</h5>
            <p class="text-muted">Sell your produce locally to reduce transportation emissions and support your community.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Did You Know Section -->
  <div class="card shadow-lg border-0 p-4 animate__animated animate__fadeIn">
    <h2 class="text-success mb-4 text-center">🌱 Did You Know?</h2>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.1s">
        <div class="alert alert-info shadow-sm">
          <strong>1. Recycling one aluminum can</strong> saves enough energy to run a TV for 3 hours!
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.2s">
        <div class="alert alert-info shadow-sm">
          <strong>2. Buying products made from recycled materials</strong> helps reduce landfill waste and conserves natural resources.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.3s">
        <div class="alert alert-info shadow-sm">
          <strong>3. Every ton of recycled paper</strong> saves 17 trees, 7,000 gallons of water, and 4,000 kWh of energy.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.4s">
        <div class="alert alert-info shadow-sm">
          <strong>4. Recycled plastic</strong> can be used to create clothing, bags, and even furniture — shop smart!
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.5s">
        <div class="alert alert-info shadow-sm">
          <strong>5. E-waste contains valuable metals</strong> like gold, silver, and copper — always donate or recycle your gadgets.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.6s">
        <div class="alert alert-info shadow-sm">
          <strong>6. Choosing local & sustainable products</strong> lowers your carbon footprint and supports the community.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.7s">
        <div class="alert alert-info shadow-sm">
          <strong>7. Compostable packaging</strong> breaks down naturally and reduces plastic pollution — check before you buy!
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.8s">
        <div class="alert alert-info shadow-sm">
          <strong>8. You can earn rewards</strong> by recycling correctly — check out local programs and apps that offer points or cashback.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 0.9s">
        <div class="alert alert-info shadow-sm">
          <strong>9. Reusable bags</strong> can replace hundreds of single-use plastic bags every year.
        </div>
      </div>

      <div class="col animate__animated animate__fadeIn" style="animation-delay: 1s">
        <div class="alert alert-info shadow-sm">
          <strong>10. Recycling one glass bottle</strong> saves enough energy to power a computer for 25 minutes.
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chatbot Toggle Button -->
<button class="chat-toggle pulse" onclick="toggleChat()"><i class="bi bi-chat-dots-fill"></i></button>

<!-- Chatbot Container -->
<div class="chat-container" id="chatbot">
  <div class="chat-header">AgriBot 🌱</div>
  <div class="chat-box" id="chat-box">
    <div class="message bot">Hello there! I'm AgriBot, your sustainable farming assistant. What's your name? 👩‍🌾</div>
  </div>
  <div class="chat-input">
    <input type="text" id="user-input" placeholder="Ask about recycling, farming..." />
    <button onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
    <button onclick="startVoice()" title="Speak"><i class="bi bi-mic-fill"></i></button>
  </div>
</div>

<!-- JS -->
<script>
  const responses = {
    "how to buy": "🛍 <strong>How to Buy:</strong>\n1. Go to 'Marketplace'.\n2. Browse available agricultural waste products.\n3. Click 'Buy Product'.\n4. Fill in the details and confirm your purchase.\n\nWe support sustainable farming practices!",
    "wishlist": "💚 <strong>Wishlist Features:</strong>\n- Save your favorite agricultural products\n- Get notified when prices drop\n- Quick access to items you're interested in\n\nSustainable shopping made easy!",
    "view history": "📜 <strong>Order History:</strong>\n- View all your past purchases\n- Track delivery status\n- Download invoices\n- Leave feedback for farmers\n\nThank you for supporting sustainable agriculture!",
    "community forum": "👥 <strong>Community Forum:</strong>\n- Connect with other buyers and farmers\n- Share sustainable farming tips\n- Ask questions about agricultural waste\n- Learn about recycling practices\n\nJoin our growing community today!"
  };

  let buyerName = "";

  function toggleChat() {
    const chatbot = document.getElementById("chatbot");
    chatbot.classList.toggle("show");
    
    // Remove pulse animation when chat is opened
    if (chatbot.classList.contains("show")) {
      document.querySelector('.chat-toggle').classList.remove('pulse');
    } else {
      document.querySelector('.chat-toggle').classList.add('pulse');
    }
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
        const reply = `Nice to meet you, ${buyerName}! 🌾 How can I help you today?`;
        appendMessage(reply, "bot");
        speak(reply);
        setTimeout(showOptions, 500);
      } else if (responses[userMessage]) {
        appendMessage(responses[userMessage], "bot");
        speak(responses[userMessage]);
        askMoreHelp();
      } else {
        const msg = "I'm here to help with sustainable farming and recycling. Please choose an option below 👇";
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
    msg.innerHTML = text; // Changed to innerHTML to support formatting
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
      btn.textContent = opt.charAt(0).toUpperCase() + opt.slice(1).replace(/([A-Z])/g, ' $1');
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
      const msg = "Is there anything else I can help you with today?";
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
      no.textContent = "No, thanks";
      no.onclick = () => {
        const bye = "Happy farming! Remember to recycle and support sustainable agriculture. 🌍";
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
    // Remove HTML tags from text for speech
    const cleanText = text.replace(/<[^>]*>/g, '');
    const utterance = new SpeechSynthesisUtterance(cleanText);
    speechSynthesis.speak(utterance);
  }

  function startVoice() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      appendMessage("Sorry, your browser doesn't support voice recognition.", "bot");
      return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "en-US";
    recognition.interimResults = false;

    recognition.onstart = function() {
      appendMessage("Listening... Speak now", "bot");
    };

    recognition.onresult = function(event) {
      const transcript = event.results[0][0].transcript;
      document.getElementById("user-input").value = transcript;
      sendMessage();
    };

    recognition.onerror = function(event) {
      appendMessage("Error occurred in recognition: " + event.error, "bot");
    };

    recognition.start();
  }

  // Auto-focus input when chat is opened
  document.getElementById('chatbot').addEventListener('click', function() {
    document.getElementById('user-input').focus();
  });

  // Send message on Enter key
  document.getElementById('user-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      sendMessage();
    }
  });

  // Initialize animations
  document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on scroll
    const animateOnScroll = function() {
      const elements = document.querySelectorAll('.animate__animated');
      
      elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        
        if (elementPosition < windowHeight - 100) {
          const animationClass = element.classList[1];
          element.classList.add(animationClass);
        }
      });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on load
  });
</script>

</body>
</html>