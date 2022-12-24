<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
</head>

<body>

    <script >
        window.jsPDF = require('jspdf');
        var doc = new jsPDF("landscape");
        doc.text("Hello landscape world!", 20, 20);
    </script>
</body>

</html>