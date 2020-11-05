<template>
  <div v-show="checkPassword!='' && !isOk">
    <span class="conditions_arrow conditions mx-auto " :style="'left: '+ pleft + 'px;'">
      <h4 class="p-3 mb-0">{{$t('tip.passwordTitle')}}</h4>
      <ul class="cond pl-2">
        <li v-for="cond in conditions" :class="{'ok':cond.ok, 'un':!cond.ok}">{{cond.tip}}</li>
      </ul>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String
    },
    width: {
      type: Number,
      default: 310
    },
    pinput: {
      type: Object,
      default: null
    }
  },
  watch: {
    value(val, old) {
      if (val != old) {
        this.checkPassword = val;
        this.check();
      }
    }
  },
  mounted() {
    this.checkPassword = this.value;
    this.$nextTick(function() {
      this.init();
    });
    this.pleft = (this.width - 240) / 2;
    if (this.pinput) {
    }
  },
  data() {
    return {
      checkPassword: "",
      conditions: [],
      isOk: false,
      pleft: (310 - 240) / 2
    };
  },
  methods: {
    init() {
      this.conditions = [
        {
          rule: "(?=.{8,})",
          tip: this.$i18n.t("tip.password.0"),
          ok: false
        },
        {
          rule: "(?=.*[a-z])(?=.*[A-Z])",
          tip: this.$i18n.t("tip.password.1"),
          ok: false
        },
        {
          rule: "(?=.*[0-9])",
          tip: this.$i18n.t("tip.password.2"),
          ok: false
        }
      ];
    },
    check() {
      let ok = true;
      for (var i = 0; i < this.conditions.length; i++) {
        if (this.conditions[i].rule != "") {
          let r = new RegExp(this.conditions[i].rule);
          if (r.test(this.checkPassword)) {
            this.conditions[i].ok = true;
          } else {
            this.conditions[i].ok = false;
            ok = false;
          }
        }
      }
      this.isOk = ok;
      this.$emit("success", ok);
    }
  }
};
</script>
<style type="text/css">
.conditions {
  display: block;
  position: absolute;
  top: 72px !important;
  width: 240px !important;
  height: 160px !important;
  border: 1px solid #d5d5da;
  background-color: #f2f2f2;
  border-radius: 5px;
}

.conditions:after,
.conditions:before {
  bottom: 100%;
  left: 50%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.conditions:after {
  border-color: rgba(255, 255, 255, 0);
  border-bottom-color: #f2f2f2;
  border-width: 12px;
  margin-left: -12px;
}
.conditions:before {
  border-color: rgba(59, 66, 162, 0);
  border-bottom-color: #d5d5da;
  border-width: 13px;
  margin-left: -13px;
}
.conditions h4 {
  font-size: 16px;
}
.cond {
  list-style: none;
}

.cond li {
  padding-left: 30px;
  height: 30px;
}

.cond li.un {
  color: #707070;
  background: url("/img/icon/uncomplate.svg") no-repeat;
  background-size: 24px 24px;
}

.cond li.ok {
  color: #000000;
  background: url("/img/icon/complete.svg") no-repeat;
  background-size: 24px 24px;
}
</style>
