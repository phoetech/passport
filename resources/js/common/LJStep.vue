<template>
    <div :class="custom">
        <span class="circle" v-for="s in stepList" :class="clickCss() + ' ' + s.c" @click="changeTo(s.n +1)"></span>
    </div>
</template>

<script>
const isFunction = maybeFunc =>
    Object.prototype.toString.call(maybeFunc) === "[object Function]";
export default {
    props: {
        count: {
            type: Number,
            default: 2
        },
        custom: {
            type: String,
            default: "ys-step"
        },
        size: {
            type: Number,
            default: 15
        },
        current: {
            type: Number,
            default: 0
        },
        onChange: {
            type: Function,
            default: null
        }
    },
    mounted() {
        this.currentStep = this.current;
        this.changeCount(this.count);
    },
    data() {
        return {
            currentStep: 0,
            stepList: [],
            stepCount: 2
        };
    },
    methods: {
        init() {
            this.stepList = [];
            for (var i = 0; i < this.stepCount; i++) {
                this.stepList.push({ n: i, c: "" });
            }
            this.stepList[this.currentStep].c = "ys-step-sel";
        },
        goTo(step) {
            this.stepList[this.currentStep].c = "ys-step-unsel";
            this.currentStep = step - 1;
            this.stepList[this.currentStep].c = "ys-step-sel";
        },
        changeTo(step) {
            if (isFunction(this.onChange)) {
                this.onChange(step);
                this.goTo(step);
            }
        },
        clickCss() {
            return isFunction(this.onChange) ? "c-hand" : "";
        },
        changeCount(stepCount) {
            this.stepCount = stepCount;
            this.init();
        }
    }
};
</script>
<style type="text/css">
.ys-step {
}
.ys-step .c-hand {
    cursor: pointer;
}
.ys-step .circle {
    background-color: #ececec;
    padding: 0px;
    margin: 3px;
    width: 15px;
    height: 15px;
    border-radius: 7px;
    display: inline-block;
}
.ys-step .current {
    background-color: #3bc9d6 !important;
    width: 30px !important;
}
/* Safari 4.0 - 8.0 */
@-webkit-keyframes sel {
    from {
        width: 15px;
        background-color: #ececec;
    }
    to {
        width: 30px;
        background-color: #3bc9d6;
    }
}
/* Standard syntax */
@keyframes sel {
    from {
        width: 15px;
        background-color: #ececec;
    }
    to {
        width: 30px;
        background-color: #3bc9d6;
    }
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes unsel {
    from {
        width: 30px;
        background-color: #3bc9d6;
    }
    to {
        width: 15px;
        background-color: #ececec;
    }
}
/* Standard syntax */
@keyframes unsel {
    from {
        width: 30px;
        background-color: #3bc9d6;
    }
    to {
        width: 15px;
        background-color: #ececec;
    }
}
.ys-step-sel {
    animation-name: sel;
    animation-duration: 0.5s;
    animation-fill-mode: forwards;
}
.ys-step-unsel {
    animation-name: unsel;
    animation-duration: 0.5s;
    animation-fill-mode: forwards;
}
</style>
