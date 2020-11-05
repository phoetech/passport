<template>
    <div class="row no-gutters justify-content-center forget-password">
        <div class="card card-default border-0 login-card">
            <div class="card-body">
                <h2>
                    {{$t('forgetPassword')}}
                    <span class="step float-right">
                        <lj-step ref="vstep" :count="stepCount"></lj-step>
                    </span>
                </h2>
                <form method="post" @submit.prevent="submit">
                    <input type="hidden" name="phone" v-model="phone" ref="phone" v-validate="'required|min:8|max:12'">
                    <input type="hidden" name="_token" :value="csrf_token">
                    <input type="hidden" name="ccc" v-model="countryCallCode">
                    <input type="hidden" name="cc" v-model="countryCode">
                    <div v-show="step==1">
                        <intel-phone ref="intPhone" :placeholder="$t('phone')" :to-front="preferredCountries" v-model="phone" :onChange="onInput" :onInit="onInit" :code="getDefaultCountry()" :cClass="errors.has('phone')?'err':''"></intel-phone>
                        <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
                        <button v-if="!isSubmit" @click="checkPhone" class="ysbtn">{{$t('confirm')}}</button>
                        <button v-if="isSubmit"><img src="/img/loading.gif" class="loading-button"></button>
                        
                    </div>
                    <div v-show="step==2">
                        <div class="pincode">
                            <input :class="{'err': errors.has('code') }" type="text" name="code" maxlength="6" v-model="code" v-validate="'required|digits:6'" :placeholder="$t('pincode')">
                            <a href="#" class="blue send-code" @click="sendCode">
                                {{ coolDown>0 ? coolDown + $t("coolDown") : (isSend ? $t('sendCodeSend'):$t('sendCode'))}}
                            </a>
                        </div>
                        <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
                        <button v-if="!isSubmit" @click="checkCode" class="ysbtn">{{$t('confirm')}}</button>
                        <button v-if="isSubmit"><img src="/img/loading.gif" class="loading-button"></button>
                    </div>
                    <div v-show="step==3 && idVerify">
                        <input type="text" :placeholder="$t('id_name')" name="id_name" id="id_name" v-validate="'required|min:2|max:30'" v-model="id_name" v-if="idVerify">
                        <input type="text" :placeholder="$t('id_number')" name="id_number" id="id_number" v-validate="'required|min:3|max:30'" v-model="id_number" v-if="idVerify">
                        <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
                        <button v-if="!isSubmit" @click="checkTrueId" class="ysbtn">{{$t('confirm')}}</button>
                        <button v-if="isSubmit"><img src="/img/loading.gif" class="loading-button"></button>
                    </div>

                    <div v-show="(step==3 && !idVerify) || (idVerify && step ==4)">
                        <div class="position-relative">
                            <input type="password" ref="password" :class="{'err': errors.has('password') }" :placeholder="$t('password')" name="password" id="password" v-validate="{ required: true, regex: /(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/ }" v-model="password">
                            <LJPassword v-model="password" :width="passwordWidth" v-if="passwordWidth>0"></LJPassword>
                            <input type="password" :class="{'err': errors.has('password_confirmation') }" :placeholder="$t('password_confirmation')" name="password_confirmation" id="password_confirmation" v-validate="'required|confirmed:password'">
                        </div>
                        <label v-if="errorMsg" class="errorMsg">{{errorMsg}}</label>
                        <button v-if="!isSubmit" @click="changePassword" class="ysbtn">{{$t('changePassword')}}</button>
                        <button v-if="isSubmit" class="ysbtn"><img src="/img/loading.gif" class="loading-button"></button>
                    </div>
                    <!-- <button v-if="submitLoading" class="" @click="test"><img src="/img/loading.gif" class="loading-button"></button> -->
                </form>
            </div>
        </div>
        <div class="text-center register-tip">{{$t('alreadyRegister')}} <a href="/login" class="blue">{{$t('login')}}</a></div>
        <form action="/login" method="post" id="loginForm" ref="loginForm" v-on:submit.prevent="submitLogin">
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
import LJPassword from "../common/LJPassword.vue";
export default {
    components: {
        LJPassword
    },
    mounted() {
        this.inviteCode = this.refer;
    },
    data() {
        return {
            preferredCountries: window.__preferredCountries,
            phone: "",
            countryCode: "",
            countryCallCode: "",
            code: "",
            errorMsg: null,
            submitLoading: false,
            csrf_token: $("meta[name=csrf-token]").attr("content"),
            coolDown: 0,
            isSend: false,
            step: 1,
            id_name: "",
            id_number: "",
            password: "",
            isSubmit: false,
            stepCount: 3,
            idVerify: false,
            passwordWidth: 0
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
        getDefaultCountry() {
            let cc = this.$cookie.get("cc");
            if (cc) {
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
        sendCode() {
            var self = this;
            this.$validator.validate("phone").then(result => {
                if (result) {
                    this.isSend = true;
                    this.$http
                        .post("/auth/code/send", {
                            ccc: self.countryCallCode,
                            phone: self.phone
                        })
                        .then(response => {
                            return response.json();
                        })
                        .then(data => {
                            this.isSend = false;
                            self.coolDown = data.data.time;
                            setTimeout(self.cd, 1000);
                        });
                }
            });
        },
        checkPhone() {
            this.errorMsg = "";
            this.$validator.validate("phone").then(result => {
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
                                data.data.display_id == undefined ||
                                data.data.display_id == 0
                            ) {
                                this.isSubmit = false;
                                this.errorMsg = this.$i18n.t("notFoundUser");
                                return false;
                            } else {
                                this.idVerify = data.data.id_verify;
                                if (data.data.id_verify) {
                                    this.$refs.vstep.changeCount(4);
                                    this.stepCount = 4;
                                }
                                this.step = 2;
                                this.isSubmit = false;

                                this.$refs.vstep.goTo(this.step);
                                return true;
                            }
                        });
                }
            });
        },
        checkCode() {
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
                                        if (data.data.display_id == undefined) {
                                            this.isSubmit = false;
                                            this.errorMsg = this.$i18n.t(
                                                "notFoundUser"
                                            );
                                            return false;
                                        } else {
                                            this.errorMsg = null;
                                            this.step = 3;
                                            this.isSubmit = false;

                                            this.$refs.vstep.goTo(this.step);
                                            this.code = data.data.code;
                                            this.$nextTick(function() {
                                                this.passwordWidth = this.$refs.password.offsetWidth;
                                            });
                                            return false;
                                        }
                                    } else {
                                        this.errorMsg = data.msg;
                                    }
                                });
                        }
                    });
                }
            });
        },
        checkTrueId() {
            this.$validator.validate().then(result => {
                this.isSubmit = true;
                this.$http
                    .get(
                        "/auth/trueId/check?ccc=" +
                            this.countryCallCode +
                            "&phone=" +
                            this.phone +
                            "&code=" +
                            this.code +
                            "&id_name=" +
                            this.id_name +
                            "&id_number=" +
                            this.id_number
                    )
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        this.isSubmit = false;
                        if (data.status == "success") {
                            this.errorMsg = null;
                            this.step = 4;
                            this.$refs.vstep.goTo(this.step);
                            this.$nextTick(function() {
                                this.passwordWidth = this.$refs.password.offsetWidth;
                            });
                        } else {
                            if (data.errors) {
                                this.errorMsg =
                                    data.errors[Object.keys(data.errors)[0]][0];
                            } else {
                                this.errorMsg = data.msg;
                            }
                        }
                    });
            });
        },
        changePassword() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isSubmit = true;
                    this.$http
                        .put("/auth/password", {
                            ccc: this.countryCallCode,
                            phone: this.phone,
                            code: this.code,
                            id_name: this.id_name,
                            id_number: this.id_number,
                            password: this.password,
                            confirmPassword: this.confirmPassword
                        })
                        .then(response => {
                            return response.json();
                        })
                        .then(data => {
                            this.isSubmit = false;
                            if (data.status == "success") {
                                this.errorMsg = null;
                                location.href = "/login";
                            } else {
                                if (data.errors) {
                                    this.errorMsg =
                                        data.errors[
                                            Object.keys(data.errors)[0]
                                        ][0];
                                } else {
                                    this.errorMsg = data.msg;
                                }
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
        submit(e) {
            return false;
        },
        changeTo(step) {
            this.step = step;
        }
    }
};
</script>
<style type="text/css">
</style>
