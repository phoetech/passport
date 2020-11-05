<template>
    <modal v-model="confirmModel" class="ysconfirm" width="350px" :backdrop="true">
        <div slot="modal-header" class="modal-header" v-show='false'>
        </div>
        <div slot="modal-body" class="modal-body text-center">
            <label class="modal-title">{{title}}</label>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button class="ysbtn btn-gray" :disabled="isLoading" @click="no">{{$t('title.no')}}</button>
            <button class="ysbtn btn-red-bg" v-if="!isLoading" @click="yes">{{$t('title.yes')}}</button>
            <button v-if="isLoading" class="ysbtn btn-red-bg"><img src="/img/loading.gif" class="loading-button"></button>
        </div>
    </modal>
</template>

<script>
import { modal } from "vue-strap";

export default {
    components: {
        modal
    },
    data() {
        return {
            confirmModel: false,
            callYes: null,
            callNo: null,
            title: "",
            isLoading: false
        };
    },
    methods: {
        show(title, callYes, callNo) {
            this.title = title;
            this.callYes = callYes;
            this.callNo = callNo;
            this.confirmModel = true;
        },
        hide() {
            this.confirmModel = false;
            this.isLoading = false;
        },
        yes() {
            this.isLoading = true;
            if (this.callYes) {
                this.callYes();
            }
        },
        no() {
            this.confirmModel = false;
            if (this.callNo) {
                this.callNo();
            }
        }
    }
};
</script>
<style type="text/css">
.modal-title {
    font-size: 20px !important;
}
.ljconfirm .modal-content {
    margin-top: 200px !important;
}
</style>
