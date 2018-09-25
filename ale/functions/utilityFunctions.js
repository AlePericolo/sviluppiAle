function isEmpty(str) {
    return (!str || 0 === str.length);
}

function round2decimal(number){
    return parseFloat(Math.round(number * 100) / 100).toFixed(2);
}