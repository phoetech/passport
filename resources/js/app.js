/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import "@babel/polyfill";
require("./bootstrap");
import "bootstrap/js/dist/util";

window.Vue = require("vue");
var VueCookie = require("vue-cookie");
// Tell Vue to use the plugin
Vue.use(VueCookie);

import VueI18n from "vue-i18n";
Vue.use(VueI18n);

import lang from "./data/AuthLang.js";
const i18n = new VueI18n({
  locale: "en", // set locale
  messages: lang // set locale messages
});

import VueMoment from "vue-moment";
import moment from "moment-timezone";
Vue.use(VueMoment, {
  moment
});

import VeeValidate from "vee-validate";
const validateConfig = {
  errorBagName: "errors", // change if property conflicts.
  fieldsBagName: "fields",
  delay: 500,
  locale: "en",
  dictionary: null,
  strict: true,
  enableAutoClasses: true,
  classNames: {
    touched: "touched", // the control has been blurred
    untouched: "untouched", // the control hasn't been blurred
    valid: "valid", // model is valid
    invalid: "invalid", // model is invalid
    pristine: "pristine", // control has not been interacted with
    dirty: "dirty" // control has been interacted with
  },
  events: "input|blur|change",
  inject: true
};
Vue.use(VeeValidate, validateConfig);

var VueResource = require("vue-resource");
Vue.use(VueResource);

Vue.http.headers.common["X-CSRF-TOKEN"] = $("meta[name=csrf-token]").attr(
  "content"
);
Vue.http.headers.common["Accept"] = "application/json";
Vue.http.headers.common["CLIENT"] = "web";
Vue.http.headers.common["TIMEZONE"] = Vue.moment.tz.guess();
Vue.http.interceptor.before = function(request) {
  request.url = "/api" + request.url;
};
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.__preferredCountries = ["us", "cn", "jp", "de", "gb", "fr"];
Vue.component("lj-input", require("./common/LJInput.vue").default);
Vue.component("lj-step", require("./common/LJStep.vue").default);
Vue.component("intel-phone", require("./common/IntelPhone.vue").default);
Vue.component("login", require("./auth/login.vue").default);
Vue.component("register", require("./auth/register.vue").default);
Vue.component("forget", require("./auth/forget.vue").default);
Vue.component("lj-app-download", require("./common/LJAppDownload.vue").default);
const app = new Vue({
  el: "#app",
  delimiters: ["{[", "]}"],
  i18n,
  data: {
    lang: "en",
    langList: {
      en: "English",
      zh: "简体中文",
      es: "Español"
    },
    backTo: false,
    productStepCount: 4,
    productCurrentCount: 1,
    isAndroid: false,
    appCommon: window.APPCommon,
    showAppDownload: false
  },
  mounted() {
    let cookieLang = this.$cookie.get("lang");
    if (cookieLang && this.langList[cookieLang] != undefined) {
      this.lang = cookieLang;
    } else {
      this.lang = window.LJ.lang;
    }
    this.$i18n.locale = this.lang;
    Vue.http.headers.common["LANG"] = this.lang;
    window.addEventListener("scroll", this.showBackTo);
    this.initProductSlide();
    this.isAndroid = navigator.userAgent.match(/Android/i);
    // this.isAndroid = false;
  },
  methods: {
    showBackTo() {
      this.backTo = window.scrollY > 200;
    },
    setLang(la) {
      if (this.langList[la] != undefined) {
        this.$cookie.set("lang", la, 365);
        Vue.http.headers.common["LANG"] = this.lang;
        this.lang = la;
        this.$i18n.locale = this.lang;
        location.reload();
      }
    },
    screenDown(elName) {
      let top = 0;
      if (this.isNumeric(elName)) {
        top = elName;
      } else {
        top = document.getElementById(elName).getBoundingClientRect().top;
      }
      window.scrollTo({
        top: top,
        behavior: "smooth"
      });
    },
    initProductSlide() {
      let self = this;
      $("#productsList").on("slide.bs.carousel", function(event) {
        if (event.direction == "left") {
          self.productCurrentCount += 1;
          if (self.productCurrentCount > self.productStepCount)
            self.productCurrentCount = 1;
          self.$refs.vstep.goTo(self.productCurrentCount);
        } else {
          self.productCurrentCount -= 1;
          if (self.productCurrentCount == 0)
            self.productCurrentCount = self.productStepCount - 1;
          self.$refs.vstep.goTo(self.productCurrentCount);
        }
      });
    },
    isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    },
    checkAppInstall() {
      this.appCommon.openApp();
      this.showAppDownload = true;
    }
  }
});
