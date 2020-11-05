import Vue from "vue";
function currency(value, point = true, currencyCountry = "USD") {
    // if (!value && value != 0) {
    //     return "";
    // }
    // let minDigits = 0;
    // if (point) {
    //     minDigits = 2;
    // }
    // const formatter = Intl.NumberFormat("en-US", {
    //     minimumFractionDigits: minDigits,
    //     style: "currency",
    //     currency: currencyCountry
    // });
    // let s = +value / 100;
    // if (!point) {
    //     s = +parseInt(value / 100);
    // }
    // return formatter.format(s);

    let amount = parseFloat(value);
    if (_.isNaN(amount)) {
        return "-";
    }
    const amountString = Math.abs(amount / 100)
        .toFixed(2)
        .toLocaleString()
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return "$" + amountString;
}

function numberFormat(value) {
    if (!value && value != 0) {
        return "";
    }
    const formatter = Intl.NumberFormat("en-US", {});
    return formatter.format(value);
}

function subContent(value, len) {
    return value.substring(0, len);
}

function alias(value, name) {
    return value == "" ? name : value;
}

// function bigCurrency(value) {
//     if (!value && value != 0) {
//         return "";
//     }
//     const formatter = Intl.NumberFormat("en-US", {
//         minimumFractionDigits: 2,
//         style: "currency",
//         currency: "USD"
//     });
//     let s = formatter.format(value / 100);
//     let ss = s.split(".");
//     s = ss[0] + "<sup>." + ss[1] + "</sup>";
//     return s;
// }

function dataFormat(value, format) {
    return Vue.moment(value.replace(" ", "T") + "Z").format(format);
}

function UpperCase(value) {
    if (value) {
        return value.toUpperCase();
    }
    return "-";
}

export default {
    install(Vue) {
        Vue.filter("upperCase", UpperCase);
        Vue.filter("date", dataFormat);
        // Vue.filter("bigCurrency", bigCurrency);
        Vue.filter("currency", currency);
        Vue.filter("numberFormat", numberFormat);
        Vue.filter("subContent", subContent);
        Vue.filter("alias", alias);
    }
};
