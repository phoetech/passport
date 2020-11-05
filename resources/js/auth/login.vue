<template>
  <div class="login-aside">
    <div id="o-box-down">
      <div class="card-body">
        <h2 class="fl">{{$t('login')}}</h2>
        <span class="step2 fr">
          PHOETECH.COM
        </span>
        <form method="post">
          <!-- <div class="intl-phone-input allow-dropdown int-phone">
            <div class="flag-container">
              <div class="selected-flag">
                <div class="iti-flag us"></div>
                <div class="iti-arrow"></div>
              </div>
            </div>
            <input type="text" autocomplete="off" name="tel-input-1" :placeholder="$t('phone')" maxlength="12">
          </div> -->
          <intel-phone
            ref="intPhone"
            :placeholder="$t('phone')"
            :to-front="preferredCountries"
            v-model="phone"
            :onChange="onInput"
            :onInit="onInit"
            :code="getDefaultCountry()"
            class="int-phone"
          ></intel-phone>
          <input type="password" name="password" :placeholder="$t('loginPassword')"> <!----> 
          <button class="ysbtn">{{$t('login')}}</button>
        </form>
        <div class="register-top">
          <span class="fl"><a href="/forget" >{{$t('forgetPassword')}}</a></span>
          <span class="fr">{{$t('doNotHaveAccount')}}
      <a href="/register" class="blue">{{$t('register')}}</a></span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  mounted() {
    this.errorMsg = this.error;
    // this.defaultCode = this.getDefaultCountry();

    let phone = this.$cookie.get("default_phone");
    if (phone != undefined && phone != "undefined") {
      this.phone = phone;
    }
  },
  data() {
    return {
      preferredCountries: window.__preferredCountries,
      phone: "",
      countryCode: "",
      countryCallCode: "",
      password: "",
      errorMsg: null,
      submitLoading: false,
      // defaultCode: "us",
      csrf_token: $("meta[name=csrf-token]").attr("content")
    };
  },
  computed: {
    identifier: function() {
      return this.countryCallCode + this.phone;
    }
  },
  props: {
    error: String,
    lang: String
  },
  methods: {
    getDefaultCountry() {
      let cc = this.$cookie.get("cc");
      if (cc != undefined && cc != "undefined") {
        return cc;
      }
      return "us";
    },
    onInput(obj) {
      this.countryCode = obj.countryCode;
      this.countryCallCode = obj.dialCode;
      this.$cookie.set("cc", this.countryCode, 365);
    },
    onInit(obj) {
      this.countryCode = obj.countryCode;
      this.countryCallCode = obj.dialCode;
    },
    onLogin(e) {
      this.$cookie.set("default_phone", this.phone, 30);
      return true;
    },
    login() {
      this.submitLoading = true;
      let data = {
        identifier: this.countryCallCode + this.phone,
        password: this.password
      };
      this.$http
        .post("/auth/login", data)
        .then(response => {
          return response.json();
        })
        .then(data => {
          if (data.status == "success") {
            location.href = "/home";
          } else {
            this.errorMsg = data.msg;
          }
          this.submitLoading = false;
        });
    },
    test() {
      this.submitLoading = false;
    }
  }
};
</script>