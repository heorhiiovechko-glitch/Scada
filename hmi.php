<?php
// simple HMI mockup page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suzlon HMI Panel</title>
    <style>
        body {
            background-color: #007C80;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .panel {
            background: white;
            border: 5px solid #444;
            margin: 40px auto;
            width: 650px;
            height: 400px;
            position: relative;
            border-radius: 6px;
        }
        .screen {
            position: absolute;
            left: 150px;
            top: 40px;
            width: 320px;
            height: 200px;
            background: black;
            color: #0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            border: 3px inset #444;
        }
        .btn {
            padding: 6px 10px;
            margin: 4px;
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .stop { background: red; }
        .start { background: green; }
        .reset { background: blue; }
        .arrow { background: skyblue; }
        .ctrl { background: purple; }
        .func, .key { background: #333; color: #fff; }

        .left-buttons {
            position: absolute;
            top: 40px;
            left: 20px;
        }
        .func-left {
            position: absolute;
            top: 40px;
            left: 100px;
        }
        .func-right {
            position: absolute;
            top: 40px;
            right: 100px;
        }
        .keypad {
            position: absolute;
            bottom: 40px;
            right: 40px;
            display: grid;
            grid-template-columns: repeat(3, 60px);
            gap: 6px;
        }
    </style>
</head>
<body>
    <h2 style="color:white;">SUZLON HMI Mockup</h2>
    <div class="panel">
        <!-- Display -->
        <div class="screen">Suzlon Display</div>

        <!-- Left side buttons -->
        <div class="left-buttons">
            <button class="btn stop">STOP</button><br>
            <button class="btn start">START</button><br>
            <button class="btn reset">RESET</button><br>
            <button class="btn arrow">↑</button><br>
            <button class="btn ctrl">CTRL</button>
        </div>

        <!-- Function keys left -->
        <div class="func-left">
            <button class="btn func">F1</button><br>
            <button class="btn func">F2</button><br>
            <button class="btn func">F3</button><br>
            <button class="btn func">F4</button><br>
            <button class="btn func">F5</button>
        </div>

        <!-- Function keys right -->
        <div class="func-right">
            <button class="btn func">F6</button><br>
            <button class="btn func">F7</button><br>
            <button class="btn func">F8</button><br>
            <button class="btn func">F9</button><br>
            <button class="btn func">F10</button>
        </div>

        <!-- Keypad -->
        <div class="keypad">
            <button class="btn key">ESC</button>
            <button class="btn key">HOME</button>
            <button class="btn key">INS</button>
            <button class="btn key">7</button>
            <button class="btn key">8</button>
            <button class="btn key">9</button>
            <button class="btn key">4</button>
            <button class="btn key">5</button>
            <button class="btn key">6</button>
            <button class="btn key">1</button>
            <button class="btn key">2</button>
            <button class="btn key">3</button>
            <button class="btn key">0</button>
            <button class="btn key">↵</button>
            <button class="btn key">←</button>
        </div>
    </div>
</body>
</html>
