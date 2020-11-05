<template>
    <modal v-model="show" class="yssuccess" width="300px" :backdrop="false">
        <div slot="modal-header" class="modal-header">
        </div>
        <div slot="modal-body" class="modal-body text-center">
            <img src="/img/success-icon.svg">
            <h2>{{$t('title.success')}}</h2>
        </div>
        <div slot="modal-footer" class="modal-footer">
        </div>
    </modal>
</template>

<script>
import { modal } from "vue-strap";

export default {
  components: {
    modal
  },
  props: {
    value: { type: Boolean, required: true }
  },
  mounted() {
    this.show = this.value;
  },
  data() {
    return {
      show: false,
      tm: null
    };
  },
  watch: {
    value(val, old) {
      if (val !== old) {
        this.show = val;
      }
    },
    show(val, old) {
      if (val) {
        this.tm = setTimeout(this.hide, 2000);
      } else {
        this.$emit("input", val);
      }
    }
  },
  methods: {
    hide() {
      if (this.show) {
        this.show = false;
      }
    }
  }
};
</script>
<style type="text/css">
.yssuccess .modal-content {
  margin-top: 200px !important;
}
</style>
