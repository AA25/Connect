function getBase64Encoded(str) {
    var wordArray = CryptoJS.enc.Utf8.parse(str);
    var result = CryptoJS.enc.Base64.stringify(wordArray);
    return result;
}

function getBase64Decoded(encodedStr) {
    var wordArray = CryptoJS.enc.Base64.parse(encodedStr);
    var result = wordArray.toString(CryptoJS.enc.Utf8);
    return result;
}

function doEncode() {
    var txtEncode = document.getElementById("txtEncode");
    var resultEncode = document.getElementById("resultEncode");
    resultEncode.innerText = getBase64Encoded(txtEncode.value);
}

function doDecode() {
    var txtDecode = document.getElementById("txtDecode");
    var resultDecode = document.getElementById("resultDecode");
    resultDecode.innerText = getBase64Decoded(txtDecode.value);
}

function createJWT() {
    var txtHeader = '{"alg":"HS256", "typ":"JWT"}';
    var txtPayload = '{"iss":"connectServer", "name":"Ade"}';
    var txtSecret = 'secret';
    var resultJWT = document.getElementById("resultJWT");

    var base64Header = getBase64Encoded(txtHeader);
    var base64Payload = getBase64Encoded(txtPayload);

    var signature = CryptoJS.HmacSHA256(base64Header + "." + base64Payload, txtSecret);
    var base64Sig = CryptoJS.enc.Base64.stringify(signature);
    console.log(JSON.stringify(signature));

    var jwt = base64Header + "." + base64Payload + "." + base64Sig;
    resultJWT.innerText = jwt;
}

// { "typ": "JWT", "alg": "HS256" }

// { "iss": "connectServer", "name": "Ade" }