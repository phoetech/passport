<template>
	<div class="LJInput" :class="{'LJInputLabelShow':showPlaceHolder}">
		<label v-if="showPlaceHolder">{{placeHolder}}</label>
		<input type="text" name="inputText" v-model="inputText" :placeholder="placeHolder">
	</div>
</template>

<script>
export default {
	components: {},
	props: {
		value: { type: String, required: true },
		placeHolder: { type: String, required: false },
		type: { type: String, required: false, default: "text" }
	},
	computed: {
		showPlaceHolder: function() {
			return this.placeHolder != "" && this.inputText != "";
		}
	},
	mounted() {
		this.inputText = this.value;
	},
	data() {
		return {
			inputText: ""
		};
	},
	watch: {
		value(val, old) {
			if (val !== old) {
				this.show = val;
			}
		},
		inputText(val, old) {
			if (val !== old) {
				this.$emit("input", val);
			}
		}
	},
	methods: {}
};
</script>
<style type="text/css">
.LJInput {
	width: 100%;
	height: 52px;
	border-radius: 31px;
	background-color: #ffffff;
	border: solid 1px #dcdcdc;
	display: inline-block;
}
.LJInput input {
	width: 100%;
	height: 32px;
	font-size: 16px;
	color: #1e205a;
	border: none;
	margin: 10px 0;
	padding: 0px 30px;
	background: transparent;
	-webkit-appearance: none;
	-webkit-font-smoothing: inherit;
	font: inherit;
}
.LJInputLabelShow input {
	margin: 0px;
	padding: 0px 30px;
	font-size: 14px;
	height: auto;
}
.LJInputLabelShow label {
	text-align: left;
	width: 100%;
	margin: 0px 0px -5px 0px;
	padding: 0px 30px;
	font-size: 12px;
	color: #737373;
	pointer-events: none;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
}
</style>
