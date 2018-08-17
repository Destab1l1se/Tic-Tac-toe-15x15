<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>15x15</title>
    <style>
        table {
            border-spacing: 0;
        }

        td {
            position: relative;
            width: 120px;
            height: 120px;
            border: 2px solid black;
        }

        td:first-child {
            border-right: none;
        }

        td:last-child {
            border-left: none;
        }

        tr:nth-child(2) td {
            border-top: none;
            border-bottom: none;
        }

        .cross {
        }

        .cross:before, .cross:after {
            position: absolute;
            left: 50%;
            top: 0;
            content: ' ';
            height: 100%;
            width: 2px;
            background-color: black;
        }

        .cross:before {
            transform: rotate(45deg);
        }

        .cross:after {
            transform: rotate(-45deg);
        }

        .circle {
            position: relative;
            margin: 0 auto;
            height: 90%;
            width: 90%;
            background-color: black;
            border-radius: 100%;
        }

        .circle:after {
            position: absolute;
            content: '';
            height: 94%;
            width: 94%;
            background-color: white;
            border-radius: 100%;
            top: 3%;
            left: 3%;
        }
    </style>
</head>
<body>
<table>
    <tbody>
    <?php
    for ($i = 0; $i < 3; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 3; $j++) {
            echo '<td><div></div></td>';
        }
        echo '</tr>';
    }
    ?>
    </tbody>
</table>

<script>
    const GAME_DIMENSION = 3;

    const CROSS_TYPE = 'x';
    const CIRCLE_TYPE = 'o';

    let type = null;
    let blocked = true;
    let conn = new WebSocket('ws://localhost:8080/websockets.php');

    // closure for handling clicks on tds
    function crossOrCircle() {
        if (blocked)
            return;

        let div = this.firstChild;
        switch (type) {
            case CROSS_TYPE:
                div.classList.add('cross');
                break;
            case CIRCLE_TYPE:
                div.classList.add('circle');
                break;
        }

        blocked = true;
        conn.send(this.parentElement.rowIndex + '' + this.cellIndex);
    }

    // adding event listeners to all tds
    let tds = document.querySelectorAll('td');

    for (let row = 0; row < GAME_DIMENSION; row++) {

        let rowsAddendum = row * GAME_DIMENSION;

        for (let column = 0; column < GAME_DIMENSION; column++) {

            let td = tds[rowsAddendum + column];

            td.addEventListener('click',crossOrCircle);
        }
    }

    conn.onopen = function (e) {
        console.log("Connection established!");
    };

    conn.onmessage = function (e) {

        // first message is always declaring type
        if (!type) {
            type = e.data;
            console.log('You are playing as ' + type);
            if (type === CROSS_TYPE)
                blocked = false;
        }
        else if (e.data === 'win') {
            console.log('You won');
        }
        else if (e.data === 'loss') {
            console.log('You lost');
        }
        // other messages send info about opponent action in format '01' (where 0 is row and 1 is column)
        else {
            let row = +e.data[0] + 1;
            let column = +e.data[1] + 1;

            let td = document.querySelector('tr:nth-child(' + row + ') td:nth-child(' + column + ')');

            td.removeEventListener('click',crossOrCircle);

            switch (type) {
                case CROSS_TYPE:
                    td.firstChild.classList.add('circle');
                    break;
                case CIRCLE_TYPE:
                    td.firstChild.classList.add('cross');
                    break;
            }
            blocked = false;
        }
    };

    window.addEventListener('beforeunload', function () {
        conn.close();
    });
</script>
</body>
</html>
