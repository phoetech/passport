<template>
  <div class="row no-gutters justify-content-center">
    <div class="card card-default border-0 login-card">
      <div class="card-body">
        <h2>
          {{$t('register')}}
          <span class="step float-right">
            <lj-step ref="ysstep" :count="3"></lj-step>
          </span>
        </h2>
        <form method="post" @submit.prevent="submit">
          <input
            type="hidden"
            name="phone"
            v-model="phone"
            ref="phone"
            v-validate="'required|min:8|max:12|numeric'"
          >
          <input type="hidden" name="_token" :value="csrf_token">
          <input type="hidden" name="ccc" v-model="countryCallCode">
          <input type="hidden" name="cc" v-model="countryCode">
          <div v-show="step==1">
            <div class="input-select" :class="{'err': errors.has('country') }">
              <select
                id="apply_id_country"
                name="country"
                v-model="countryCode"
                v-validate="'required|min:2|max:5'"
                @change="changeCountry"
              >
                <option value="0" selected>{{$t('selectCountry')}}</option>
                <option
                  v-for="(country, index) in countryList"
                  :disabled="country.code=='00'"
                  v-bind:value="country.code"
                  v-bind:key="'country'+index"
                >{{ country.name }} {{ country.in }}</option>
              </select>
            </div>
            <intel-phone
              ref="intPhone"
              :placeholder="$t('phone')"
              :to-front="preferredCountries"
              v-model="phone"
              :onChange="onInput"
              :onInit="onInit"
              :disabled="true"
              :code="countryCode"
              :cClass="errors.has('phone')?'err':''"
              :phone-length="phoneMaxLength"
            ></intel-phone>
            <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
            <button v-if="!isSubmit" @click="checkPhone" class="ysbtn">{{$t('continue')}}</button>
          </div>
          <div v-show="step==2">
            <div class="pincode">
              <input
                :class="{'err': errors.has('code') }"
                type="text"
                name="code"
                maxlength="6"
                v-model="code"
                v-validate="'required|digits:6'"
                :placeholder="$t('pincode')"
              >
              <a
                href="#"
                class="blue send-code"
                @click="sendCode"
              >{{ coolDown>0 ? coolDown + $t("coolDown") : (isSend ? $t('sendCodeSend'):$t('sendCode'))}}</a>
            </div>
            <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
            <button v-if="!isSubmit" @click="checkCode" class="ysbtn">{{$t('continue')}}</button>
            <button v-if="isSubmit">
              <img src="/img/loading.gif" class="loading-button">
            </button>
          </div>
          <div v-show="step==3">
            <!-- <input
              type="text"
              :class="{'err': errors.has('nickname') }"
              :placeholder="$t('nickname')"
              name="nickname"
              id="nickname"
              v-validate="'required|min:2|max:20'"
              v-model="nickname"
            >
            <input
              type="text"
              v-if="countryCode=='us'"
              :class="{'err': errors.has('ssn') }"
              :placeholder="$t('ssn')"
              name="ssn"
              id="ssn"
              v-validate="'required|min:9|max:9'"
              v-model="ssn"
            >-->
            <!-- <div class="position-relative">
              <input
                type="password"
                ref="password"
                :class="{'err': errors.has('password') }"
                :placeholder="$t('password')"
                name="password"
                id="password"
                v-validate="{ required: true, regex: /(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/ }"
                v-model="password"
              >
              <YSPassword v-model="password" :width="passwordWidth" v-if="passwordWidth>0"></YSPassword>
              <input
                type="password"
                :class="{'err': errors.has('password_confirmation') }"
                :placeholder="$t('password_confirmation')"
                name="password_confirmation"
                id="password_confirmation"
                v-validate="'required|confirmed:password'"
              >
            </div>-->
            <input
              type="text"
              :class="{'err': errors.has('inviteCode') }"
              :placeholder="$t('inviteCode')"
              name="inviteCode"
              id="inviteCode"
              v-model="inviteCode"
              :disabled="refer!=''"
              v-validate="'required|min:8|max:8'"
            >
            <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
            <button class="ysbtn" v-if="!isSubmit">{{$t('register')}}</button>
            <button v-if="isSubmit" class="ysbtn" @click.prevent="isSubmit=true">
              <img src="/img/loading.gif" class="loading-button">
            </button>
          </div>
          <!-- <button v-if="submitLoading" class="" @click="test"><img src="/img/loading.gif" class="loading-button"></button> -->
        </form>
      </div>
    </div>
    <div class="text-center register-tip">
      {{$t('alreadyRegister')}}
      <a href="/login" class="blue">{{$t('login')}}</a>
    </div>
    <form
      action="/login"
      method="post"
      id="loginForm"
      ref="loginForm"
      v-on:submit.prevent="submitLogin"
    >
      <input type="hidden" name="_token" :value="csrf_token">
      <input type="hidden" name="ccc" v-model="countryCallCode">
      <input type="hidden" name="lang" v-model="lang">
      <input type="hidden" name="password" v-model="password">
      <input type="hidden" name="phone" v-model="phone" ref="phone">
      <input type="hidden" name="identifier" v-model="identifier">
    </form>
  </div>
</template>

<script>
import { _processCountryData, CountryData } from "../data/CountryData.js";
import LJPassword from "../common/LJPassword.vue";

export default {
  components: {
    LJPassword
  },
  mounted() {
    this.inviteCode = this.refer;
    this._processCountryData();
  },
  data() {
    let countryCode = this.getDefaultCountry();
    return {
      preferredCountries: window.__preferredCountries,
      phone: "",
      countryList: null,
      countryCode: countryCode,
      countryCallCode: "",
      code: "",
      errorMsg: null,
      submitLoading: false,
      csrf_token: $("meta[name=csrf-token]").attr("content"),
      coolDown: 0,
      isSend: false,
      step: 1,
      nickname: "",
      ssn: "",
      password: "",
      inviteCode: "",
      isSubmit: false,
      passwordWidth: 0,
      phoneMaxLength: 12,
      // defaultCountryCode: window.ys.cc
    };
  },
  computed: {
    identifier: function() {
      return this.countryCallCode + this.phone;
    }
  },
  props: {
    refer: String,
    lang: String,
    errs: Array
  },
  methods: {
    setFormRule() {
      if (this.countryCode == "us") {
        this.phoneMaxLength = 10;
      } else if (this.countryCode == "cn") {
        this.phoneMaxLength = 11;
      } else {
        this.phoneMaxLength = 12;
      }
    },
    changeCountry(country) {
      if (this.countryCode == "0") {
        return;
      }
      this.$refs.intPhone.changeCode(this.countryCode);
      this.setFormRule();
      this.phone = "";
      this.$cookie.set("cc", this.countryCode, 365);
    },
    // 处理国家选择的优先显示数据
    _processCountryData() {
      let data = _processCountryData(this.getDefaultCountry());
      this.countryCode = data[1].code;
      this.countryList = data[0];
      this.$refs.intPhone.changeCode(this.countryCode);
      this.setFormRule();
    },
    getDefaultCountry() {
      let cc = this.$cookie.get("cc");
      if (cc) {
        return cc;
      } else if (this.defaultCountryCode) {
        return this.defaultCountryCode;
      }
      return "us";
    },
    onInput(obj) {
      this.countryCode = obj.countryCode;
      this.countryCallCode = obj.dialCode;
      this.errorMsg = "";
    },
    onInit(obj) {
      this.countryCode = obj.countryCode;
      this.countryCallCode = obj.dialCode;
    },
    sendCode() {
      if (this.coolDown > 0) return false;
      this.$validator.validate("phone").then(result => {
        if (result) {
          this.isSend = true;
          this.$http
            .post("/auth/code/send", {
              ccc: this.countryCallCode,
              phone: this.phone
            })
            .then(response => {
              return response.json();
            })
            .then(data => {
              this.isSend = false;
              this.coolDown = data.data.time;
              setTimeout(this.cd, 1000);
            });
        }
      });
    },
    checkPhone() {
      this.errorMsg = "";
      this.$validator.validate("phone").then(result => {
        if (result) {
          this.$validator.validate("country").then(result => {
            if (result) {
              this.$http
                .get(
                  "/auth/phone/check?ccc=" +
                    this.countryCallCode +
                    "&phone=" +
                    this.phone
                )
                .then(response => {
                  return response.json();
                })
                .then(data => {
                  if (
                    data.data.display_id != undefined &&
                    data.data.display_id > 0
                  ) {
                    this.isSubmit = false;
                    this.errorMsg = this.$i18n.t("phoneUsed");
                    return false;
                  } else {
                    this.isSubmit = false;
                    this.step = 2;
                    this.$refs.ysstep.goTo(this.step);
                  }
                });
            }
          });
        }
      });
    },
    checkCode() {
      this.errorMsg = "";
      this.$validator.validate("phone").then(result => {
        if (result) {
          this.$validator.validate("code").then(result => {
            if (result) {
              this.$http
                .get(
                  "/auth/code/check?ccc=" +
                    this.countryCallCode +
                    "&phone=" +
                    this.phone +
                    "&code=" +
                    this.code
                )
                .then(response => {
                  return response.json();
                })
                .then(data => {
                  this.isSubmit = false;
                  if (data.status == "success") {
                    if (data.data.display_id != undefined) {
                      this.isSubmit = false;
                      this.errorMsg = this.$i18n.t("phoneUsed");
                      return false;
                    } else {
                      this.isSubmit = false;
                      this.step = 3;
                      this.$refs.ysstep.goTo(this.step);
                      this.code = data.data.code;
                      // =====================
                      // 2019/1/11 简化注册
                      this.nickname = this.phoneToNickname(this.phone);
                      this.password = this.code;
                      if (this.inviteCode != 0) {
                        this.register();
                      }
                      // this.$nextTick(function() {
                      //   this.passwordWidth = this.$refs.password.offsetWidth;
                      // });
                      // =====================
                      return false;
                    }
                  } else {
                    if (typeof data.msg == "object") {
                      this.errorMsg = data.msg[Object.keys(data.msg)[0]][0];
                    } else {
                      this.errorMsg = data.msg;
                    }
                  }
                });
            }
          });
        }
      });
    },
    cd() {
      if (this.coolDown > 0) {
        this.coolDown--;
        setTimeout(this.cd, 1000);
      }
    },
    submitLogin() {
      return false;
    },
    register() {
      let data = {
        phone: this.phone,
        ccc: this.countryCallCode,
        cc: this.countryCode,
        nickname: this.nickname,
        ssn: this.ssn,
        password: this.password,
        password_confirmation: this.confirmPassword,
        code: this.code,
        inviteCode: this.inviteCode,
        lang: this.lang,
        userClient: "web"
      };

      this.$http
        .post("/auth/register", data)
        .then(response => {
          return response.json();
        })
        .then(data => {
          if (data.status == "success") {
            this.$refs.loginForm.submit();
          } else {
            if (typeof data.msg == "object") {
              if (_.isArray(data.msg[Object.keys(data.msg)[0]])) {
                this.errorMsg = data.msg[Object.keys(data.msg)[0]][0];
              } else {
                this.errorMsg = data.msg[Object.keys(data.msg)[0]];
              }
            } else this.errorMsg = data.msg;
            this.isSubmit = false;
          }
        });
    },
    submit(e) {
      if (this.step == 3) {
        this.errorMsg = null;
        this.isSubmit = true;
        this.$validator.validate().then(result => {
          if (result) {
            // e.target.submit();
            // return true;
            this.register();
          } else {
            this.isSubmit = false;
          }
        });
      }
      return false;
    },
    changeTo(step) {
      this.step = step;
    },
    phoneToNickname(phone) {
      let len = phone.length - 3 - 4;
      let mask = "";
      for (var i = 0; i < len; i++) {
        mask += "*";
      }
      return phone.substr(0, 3) + mask + phone.substr(-4);
    }
  }
};
</script>
