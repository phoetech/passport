<template>
    <div class="text-center nodata">
        <img src="/img/icon/nodata.png">
        <h4>{{noDataTip}}</h4>
        <a class="a-button ysw190" v-if="title && link" :href="link">{{title}}</a>
        <button class="ysbtn ysw190" v-if="title && cb" @click="this.cb">{{title}}</button>
    </div>
</template>
<script>
export default {
	mounted() {
		if (this.tip) {
			this.noDataTip = this.tip;
		} else {
			this.$i18n.t("tip.nodata");
		}
	},
	props: {
		tip: {
			type: String,
			default: null
		},
		title: {
			type: String,
			default: null
		},
		link: {
			type: String,
			default: null
		},
		cb: {
			type: Function,
			default: null
		}
	},
	data() {
		return {
			noDataTip: ""
		};
	}
};
</script>
<style type="text/css">
.nodata {
	margin-top: 120px;
}
.nodata img {
	max-height: 150px;
	max-width: 150px;
}
.nodata h4 {
	font-size: 20px;
	font-weight: 600;
	color: #233165;
	margin: 28px;
}
</style>
