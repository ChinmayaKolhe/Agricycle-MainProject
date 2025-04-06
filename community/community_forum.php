<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Handle New Post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "INSERT INTO community_posts (user_id, role, title, content) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $user_role, $title, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: community_forum.php");
    exit();
}

// Handle New Comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_submit'])) {
    $post_id = $_POST['post_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $query = "INSERT INTO community_comments (post_id, user_id, role, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $post_id, $user_id, $user_role, $comment);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: community_forum.php");
    exit();
}

// Get name by role and ID
function getUserName($conn, $role, $id) {
    $tableMap = [
        'farmer' => 'farmers',
        'buyer' => 'buyers',
        'insurance_agent' => 'insurance_agents',
        'admin' => 'admins'
    ];

    if (!array_key_exists($role, $tableMap)) return 'Unknown';

    $table = $tableMap[$role];
    $result = mysqli_query($conn, "SELECT name FROM $table WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['name'];
    } else {
        return 'Unknown';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #81c784;
            --dark-green: #1b5e20;
            --earth-brown: #5d4037;
            --sun-yellow: #ffd54f;
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
        
        .navbar {
            background: linear-gradient(90deg, var(--primary-green), var(--dark-green)) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid var(--sun-yellow);
        }
        
        .forum-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .page-header {
            position: relative;
            margin-bottom: 30px;
        }
        
        .page-header h2 {
            font-weight: 700;
            color: var(--dark-green);
            position: relative;
            display: inline-block;
        }
        
        .page-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
        }
        
        .new-post-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
            transition: all 0.3s;
        }
        
        .new-post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .new-post-card h5 {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.2);
        }
        
        .btn-post {
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-post:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .post-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .post-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .post-title {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .post-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .post-meta {
            font-size: 0.9rem;
            color: #777;
        }
        
        .post-author {
            color: var(--earth-brown);
            font-weight: 500;
        }
        
        .post-actions {
            margin-top: 15px;
        }
        
        .btn-edit {
            background-color: var(--sun-yellow);
            color: #333;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-edit:hover {
            background-color: #ffb300;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-delete:hover {
            background-color: #c62828;
            transform: translateY(-2px);
        }
        
        .comment-section {
            background-color: #f9f9f9;
            padding: 20px;
        }
        
        .comment-form {
            margin-bottom: 20px;
        }
        
        .btn-comment {
            background-color: var(--harvest-orange);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-comment:hover {
            background-color: #e65100;
            transform: translateY(-2px);
        }
        
        .comment {
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .comment-author {
            font-weight: 500;
            color: var(--earth-brown);
        }
        
        .comment-text {
            color: #555;
            margin: 10px 0;
        }
        
        .comment-meta {
            font-size: 0.8rem;
            color: #777;
        }
        
        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 5px;
        }
        
        .farmer-badge {
            background-color: #e8f5e9;
            color: var(--dark-green);
        }
        
        .buyer-badge {
            background-color: #e3f2fd;
            color: #1565c0;
        }
        
        .agent-badge {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        .admin-badge {
            background-color: #fff8e1;
            color: #ff8f00;
        }
        
        @media (max-width: 768px) {
            .forum-container {
                padding: 15px;
            }
            
            .new-post-card, .post-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<?php
$home_link = "#";
if ($user_role == 'farmer') {
    $home_link = '../dashboard/farmer_dashboard.php';
} elseif ($user_role == 'buyer') {
    $home_link = '../dashboard/buyer_dashboard.php';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= $home_link ?>">AgriCycle</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= $home_link ?>"><i class="bi bi-house-door"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="community_forum.php"><i class="bi bi-people-fill"></i> Community</a>
        </li>
        <li class="nav-item">
          <span class="nav-link text-white">
            <i class="bi bi-person-circle"></i> <?= ucfirst($user_role) ?>: <?= htmlspecialchars(getUserName($conn, $user_role, $user_id)) ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="forum-container">
    <div class="page-header animate__animated animate__fadeIn">
        <h2><i class="bi bi-people-fill"></i> Community Forum</h2>
        <p>Connect with farmers, buyers, and experts to share knowledge and experiences</p>
    </div>
    
    <!-- New Post Form -->
    <div class="new-post-card animate__animated animate__fadeInUp">
        <h5><i class="bi bi-pencil-square"></i> Ask a Question / Share Information</h5>
        <form method="post">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Title" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="What's on your mind?" required></textarea>
            </div>
            <button name="post_submit" class="btn btn-post">
                <i class="bi bi-send"></i> Post to Community
            </button>
        </form>
    </div>
    
    <!-- Posts List -->
    <?php
    $query = "SELECT * FROM community_posts ORDER BY created_at DESC";
    $posts = mysqli_query($conn, $query);

    if (mysqli_num_rows($posts) == 0): ?>
        <div class="text-center py-5 animate__animated animate__fadeIn">
            <i class="bi bi-chat-square-text" style="font-size: 3rem; color: var(--light-green);"></i>
            <h4 class="mt-3" style="color: var(--dark-green);">No posts yet</h4>
            <p class="text-muted">Be the first to start a discussion!</p>
        </div>
    <?php else: 
        while ($post = mysqli_fetch_assoc($posts)) {
            $poster_name = getUserName($conn, $post['role'], $post['user_id']);
            $badge_class = $post['role'] . '-badge';
    ?>
        <div class="post-card animate__animated animate__fadeInUp">
            <div class="post-header">
                <h5 class="post-title"><?= htmlspecialchars($post['title']) ?></h5>
                <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <div class="post-meta">
                    Posted by <span class="post-author"><?= htmlspecialchars($poster_name) ?></span>
                    <span class="role-badge <?= $badge_class ?>"><?= ucfirst($post['role']) ?></span>
                    on <?= date('M j, Y \a\t g:i a', strtotime($post['created_at'])) ?>
                </div>
                
                <?php if ($post['user_id'] == $user_id && $post['role'] == $user_role): ?>
                    <div class="post-actions">
                        <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn-edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?')">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Comment Section -->
            <div class="comment-section">
                <form method="post" class="comment-form">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <div class="mb-2">
                        <textarea name="comment" class="form-control" rows="2" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button name="comment_submit" class="btn-comment">
                        <i class="bi bi-chat-left-text"></i> Post Comment
                    </button>
                </form>
                
                <?php
                $post_id = $post['id'];
                $comment_query = "SELECT * FROM community_comments WHERE post_id = $post_id ORDER BY created_at ASC";
                $comments = mysqli_query($conn, $comment_query);

                if (mysqli_num_rows($comments) > 0): ?>
                    <h6><i class="bi bi-chat-left-text"></i> Comments</h6>
                    <?php while ($comment = mysqli_fetch_assoc($comments)) {
                        $commenter_name = getUserName($conn, $comment['role'], $comment['user_id']);
                        $comment_badge_class = $comment['role'] . '-badge';
                    ?>
                        <div class="comment">
                            <div class="comment-author">
                                <?= htmlspecialchars($commenter_name) ?>
                                <span class="role-badge <?= $comment_badge_class ?>"><?= ucfirst($comment['role']) ?></span>
                            </div>
                            <p class="comment-text"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            <div class="comment-meta">
                                <?= date('M j, Y \a\t g:i a', strtotime($comment['created_at'])) ?>
                            </div>
                            
                            <?php if ($comment['user_id'] == $user_id && $comment['role'] == $user_role): ?>
                                <div class="mt-2">
                                    <a href="edit_comment.php?id=<?= $comment['id'] ?>" class="btn-edit btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn-delete btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                <?php endif; ?>
            </div>
        </div>
    <?php } 
    endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize animations on scroll
    document.addEventListener('DOMContentLoaded', function() {
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