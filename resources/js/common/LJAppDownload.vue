<template>
    <modal v-model="show" 
      class="ljappdownload"
      width="300px"
      :backdrop="false"
      >
        <div slot="modal-header" class="modal-header">
        </div>
        <div slot="modal-body" class="modal-body text-center" v-show="isLoading">
            <loading></loading>
        </div>
        <div slot="modal-body" class="modal-body text-center" v-show="!isLoading">
          <div class="row no-gutter">
            <div class="col-3 offset-1">
              <img src="/img/logo-app.png" class="logo-app">
            </div>
            <div class="col-8 text-left pl-2">
              <label class="name-app d-block mb-0 pt-2">Youth Shine</label>
            <label class="ver-app d-block pl-1" v-if="ver">{{$t('tip.ver')}}: {{ver}}</label>
            </div>
          </div>
            
            
        </div>
        <div slot="modal-footer" class="modal-footer text-center" v-show="!isLoading">
            <button class="ysbtn btn-gray" @click.prevent="later">{{$t('tip.laterDownload')}}</button>
            <button class="ysbtn btn-red-bg" @click.prevent="download">{{$t('tip.nowDownload')}}</button>
        </div>
    </modal>
</template>

<script>
import { modal } from "vue-strap";
import loading from "./LJLoading.vue";
export default {
  components: {
    modal,
    loading
  },
  props: {
    value: { type: Boolean, required: true },
    ver: { type: String, default: null },
    url: { type: String, required: true }
  },
  mounted() {
    this.show = this.value;
    setTimeout(this.showButton, 3000);
  },
  data() {
    return {
      show: false,
      isLoading: true
    };
  },
  watch: {
    value(val, old) {
      if (val !== old) {
        this.show = val;
      }
    },
    show(val, old) {
      if (val !== old) {
        this.$emit("input", val);
      }
    }
  },
  methods: {
    showButton() {
      this.isLoading = false;
    },
    later() {
      this.show = false;
    },
    download() {
      if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
        location.href = this.url;
      } else if (navigator.userAgent.match(/android/i)) {
        location.href = this.url;
      } else {
        console.log("pc");
      }
    }
  }
};
</script>
<style type="text/css">
.ljappdownload .modal-content {
  margin-top: 160px !important;
}
.logo-app {
  border-radius: 12px;
  width: 60px;
}
.name-app {
  font-size: 18px;
  font-weight: bold;
}
.ver-app {
  font-size: 12px;
}
</style>
