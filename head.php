<!-- <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolve Rocket</title>
    <link rel="stylesheet" href="./support/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./support/assets/css/app.css">
    <link rel="stylesheet" href="./support/assets/css/admin.css">


</head> -->


<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Resolve Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="./css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="logo.jpg">


    <!-- <link rel="stylesheet" href="../support/assets/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="./support/assets/css/app.css">
    <!-- <link rel="stylesheet" href="../support/assets/css/admin.css"> -->

    <style>
        .chatbot {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 500px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: none;
            background-color: #ffffff;
            z-index: 9999;
            /* Ensure chatbot appears above other elements */
        }



        .chatbot.open {
            display: block;
        }

        .chatbot-header {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .close-btn {
            border: none;
            background: none;
            cursor: pointer;
            float: right;
        }

        .chatbot-body {
            height: 500px;
            overflow-y: auto;
            /* Change overflow property to 'auto' */
            padding: 10px;
        }

        .chatbot-footer {
            padding: 10px;
            display: flex;
            align-items: center;
            position: relative;
            /* Ensure relative positioning for z-index */
            z-index: 10000;
            /* Ensure chatbot footer appears above chatbot */
        }

        .chatbot-footer input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .chatbot-footer button {
            padding: 8px 15px;
            margin-left: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            z-index: 10001;
            /* Ensure send button appears above chatbot */
        }

        .open-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            z-index: 10000;
            /* Ensure toggle button appears above chatbot */
        }
    </style>
</head>