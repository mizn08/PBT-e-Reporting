<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('./support/int.php');
$success = false;
$error = false;

$db = new DB();
$ticket = '';
$ticket_id = $_GET['id'];
$this_ticket_query = $db->conn->query("SELECT * FROM tickets a JOIN users AS b ON a.userid = b.id  WHERE a.id=" . $ticket_id);
if ($this_ticket_query->num_rows > 0) {
    while ($row = $this_ticket_query->fetch_assoc()) {
        $ticket = $row;
    }
} else {
    header('Location: ./');
}
// $ticket_id = $ticket['id'];
$reps = [];
if ($ticket != '') {
    $replies = $db->conn->query("SELECT * FROM ticket_reply WHERE ticket_id =$ticket_id");
    if ($replies->num_rows > 0) {
        while ($row = $replies->fetch_assoc()) {
            $reps[] = $row;
        }
    }
}
//Reply Send Method
if (isset($_POST['submit'])) {
    $message = $_POST['message'];
    if ($db->conn->query("INSERT INTO ticket_reply (ticket_id,send_by,message) VALUES('$ticket_id','0','$message')")) {
        $success = "Reply has been sent";
        $db->conn->query("UPDATE tickets SET status=1 WHERE id=$ticket_id");
        echo "<script>
                alert('Reply has been sent');
                window.location.href = window.location.href;
              </script>";
    } else {
        $error = "Can not send reply";
    }
}
?>


<body class="sb-nav-fixed">
    <?php include 'auth.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include "sidebar.php" ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Status Ticket ID : <?php echo $ticket['ticket_id']; ?></h1>
                    <hr>


                    <div class="card mb-3">
                        <div class="card-header">
                            Ticket ID : <?php echo $ticket['ticket_id']; ?>

                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($error) && $error != false) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                            ?>
                            <?php
                            if (isset($success) && $success != false) {
                                echo '<div class="alert alert-success">' . $success . '</div>';
                            }
                            ?>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $ticket['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $ticket['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $ticket['subject']; ?></td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td><?php echo $ticket['department']; ?></td>
                                </tr>
                                <tr>
                                    <th>Problem Type</th>
                                    <td>
                                        <?php
                                        if ($ticket['problem_type'] == 'Others') {
                                            echo 'Others (' . $ticket['other_problem_type'] . ')';
                                        } else {
                                            echo $ticket['problem_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $ticket['message']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php
                                        // if ($ticket['status'] == 0) {
                                        //     echo 'Pending';
                                        // } elseif ($ticket['status'] == 2) {
                                        //     echo 'Replied';
                                        // }
                                        ?>
                                        <?php echo $ticket['status_ticket']; ?>
                                    </td>
                                </tr>
                            </table>
                            <!-- <p><?php echo $ticket['message']; ?></p> -->
                            <div class="reply-area">
                                <ul>
                                    <?php if (count($reps) > 0) { ?>
                                        <?php foreach ($reps as $k => $v) {
                                            if ($v['send_by'] == 0) {
                                        ?>
                                                <li class="reply-user">
                                                    <div class="card bg-info text-white">
                                                        <div class="card-body">
                                                            <p><?php echo $v['message']; ?></p>
                                                            <div class="text-right">
                                                                <span><?php echo $v['date']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li class="reply-me">
                                                    <div class="card bg-gray text-dark">
                                                        <div class="card-body">
                                                            <p><?php echo $v['message']; ?></p>
                                                            <div class="text-right">
                                                                <small>Send by support team at <?php echo $v['date']; ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>

                                    <?php } ?>
                                </ul>
                            </div>
                            <?php
                            if ($ticket['status_ticket'] != 'closed') {
                            ?>
                                <div class="send-area">
                                    <form method="POST">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" placeholder="Send message" id="message" cols="30" rows="4"></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <input type="hidden" name="submit" value="send">
                                            <button class="btn btn-success mt-2" type="submit">Send</button>
                                        </div>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Resolve Rocket 2024</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="chatbot" id="chatbot">
        <div class="chatbot-header">
            Chatbot
            <button class="close-btn" onclick="toggleChatbot()">X</button>
        </div>
        <div class="chatbot-body" id="chatbotBody">
            <!-- Chatbot messages will be displayed here -->
        </div>
        <div class="chatbot-footer">
            <input type="text" id="userInput" placeholder="Type a message..." onkeypress="checkEnter(event)">
            <button onclick="generateResponse()">Send</button>
        </div>
        <!-- <div id="response"></div> -->
    </div>
    <button class="open-btn" onclick="toggleChatbot()">
        <i class="fas fa-comment"></i>
    </button>

    <style>
        .user-message {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            clear: both;
            /* Ensure proper clearing for floating elements */
            max-width: 70%;
            /* Limit width to ensure responsiveness */
            float: left;
            /* Align user messages to the left */
        }

        .bot-message {
            background-color: #4BD65A;
            color: black;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            clear: both;
            /* Ensure proper clearing for floating elements */
            max-width: 70%;
            /* Limit width to ensure responsiveness */
            float: right;
            /* Align bot messages to the right */
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="./js/scripts.js"></script>


    <script src="./support/assets/js/jquery-3.6.0.min.js"></script>
    <script src="./support/assets/js/popper.min.js"></script>
    <script src="./support/assets/js/bootstrap.min.js"></script>
    <script>
        function toggleChatbot() {
            var chatbot = document.getElementById('chatbot');
            chatbot.classList.toggle('open');
        }

        function generateResponse() {
            var userInput = document.getElementById('userInput').value.trim();
            var chatbotBody = document.getElementById('chatbotBody');

            if (userInput !== '') {
                // Add user input to the chatbot body
                var userMessage = '<div class="user-message">' + userInput + '</div>';
                chatbotBody.innerHTML += userMessage;

                fetch("response.php", {
                        method: "POST",
                        body: JSON.stringify({
                            text: userInput,
                        }),
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Add response to the chatbot body with custom styling
                        var botMessage = '<div class="bot-message">' + data + '</div>';
                        chatbotBody.innerHTML += botMessage;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                // Clear user input
                document.getElementById('userInput').value = '';
            }
        }

        function checkEnter(event) {
            if (event.key === 'Enter') {
                generateResponse();
            }
        }
    </script>
</body>

</html>