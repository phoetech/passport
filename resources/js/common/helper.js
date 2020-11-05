function bigCurrency(value) {
    let amount = parseFloat(value);
    if (_.isNaN(amount)) {
        return "-";
    }
    const amountString = Math.abs(amount / 100)
        .toFixed(2)
        .toLocaleString()
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    let s = amountString;
    let ss = s.split(".");
    return "$" + ss[0] + "<sup>." + ss[1] + "</sup>";
}

function bigAmount(value) {
    let amount = parseFloat(value);
    if (_.isNaN(amount)) {
        return "-";
    }
    const amountString = Math.abs(amount / 100)
        .toFixed(2)
        .toLocaleString()
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    let s = amountString;
    let ss = s.split(".");
    return ss[0] + "<sup>." + ss[1] + "</sup>";
}

function getHashValue(key) {
    var matches = location.hash.match(new RegExp(key + "=([^&]*)"));
    return matches ? matches[1] : null;
}

module.exports.getHashValue = getHashValue;
module.exports.bigCurrency = bigCurrency;
module.exports.bigAmount = bigAmount;
