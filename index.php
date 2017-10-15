    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <input type="text" id="txtEncode">
        <button onclick="doEncode()">Encode</button>
        <br>
        <span id="resultEncode"></span>
        <br>
        <input type="text" id="txtDecode">
        <button onclick="doDecode()">Decode</button>
        <br>
        <span id="resultDecode"></span>

        <div>
            JWT Header <input type="text" id="txtHeader"><br>
            JWT Payload <input type="text" id="txtPayload"><br>
            Hash Secret <input type="text" id="txtSecret"><br>
            <button onclick="createJWT()">Show JWT</button><br>
            Result <br>
            <div id="resultJWT" style="width:600px; overflow-wrap: break-work">
            </div>
        </div>
    </body>
    <script src="js/core.js"></script>
    <script src="js/enc-base64.js"></script>
    <script src="js/hmac.js"></script>
    <script src="js/sha256.js"></script>
    <script src="js/tokenAuth.js"></script>
</html>